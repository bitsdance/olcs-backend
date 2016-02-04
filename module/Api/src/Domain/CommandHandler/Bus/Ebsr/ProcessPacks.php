<?php

/**
 * Process Ebsr packs
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Bus\Ebsr;

use Doctrine\Common\Collections\ArrayCollection;
use Dvsa\Olcs\Api\Domain\Exception;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\Bus\Ebsr\RequestMap as RequestMapQueueCmd;
use Dvsa\Olcs\Api\Service\Ebsr\FileProcessorInterface;
use Dvsa\Olcs\Api\Service\Ebsr\FileProcessor;
use Zend\Filter\Decompress;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation as OrganisationEntity;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Entity\Ebsr\EbsrSubmission as EbsrSubmissionEntity;
use Dvsa\Olcs\Api\Entity\Bus\BusReg as BusRegEntity;
use Dvsa\Olcs\Api\Entity\Bus\BusNoticePeriod as BusNoticePeriodEntity;
use Dvsa\Olcs\Api\Entity\Bus\BusServiceType as BusServiceTypeEntity;
use Dvsa\Olcs\Api\Entity\Doc\Document as DocumentEntity;
use Dvsa\Olcs\Api\Entity\System\Category as CategoryEntity;
use Dvsa\Olcs\Api\Entity\TrafficArea\TrafficArea as TrafficAreaEntity;
use Dvsa\Olcs\Api\Entity\Bus\LocalAuthority as LocalAuthorityEntity;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;
use Dvsa\Olcs\Api\Entity\Task\Task as TaskEntity;
use Dvsa\Olcs\Transfer\Command\Bus\Ebsr\ProcessPacks as ProcessPacksCmd;
use Dvsa\Olcs\Transfer\Command\Document\Upload as UploadCmd;
use Dvsa\Olcs\Api\Domain\Command\Task\CreateTask as CreateTaskCmd;
use Dvsa\Olcs\Api\Domain\Command\Bus\CreateBusFee as CreateBusFeeCmd;
use Dvsa\Olcs\Api\Domain\Command\Bus\Ebsr\CreateTxcInbox as CreateTxcInboxCmd;
use Dvsa\Olcs\Api\Domain\Command\Email\SendEbsrReceived as SendEbsrReceivedCmd;
use Dvsa\Olcs\Api\Domain\Command\Email\SendEbsrRefreshed as SendEbsrRefreshedCmd;
use Dvsa\Olcs\Api\Domain\Command\Queue\Create as CreateQueue;
use Dvsa\Olcs\Api\Entity\Queue\Queue;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\UploaderAwareInterface;
use Dvsa\Olcs\Api\Domain\UploaderAwareTrait;
use Dvsa\Olcs\Api\Domain\AuthAwareInterface;
use Dvsa\Olcs\Api\Domain\AuthAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query;
use Zend\Json\Json as ZendJson;

/**
 * Process Ebsr packs
 */
final class ProcessPacks extends AbstractCommandHandler implements
    AuthAwareInterface,
    TransactionedInterface,
    UploaderAwareInterface
{
    use AuthAwareTrait;
    use UploaderAwareTrait;

    protected $repoServiceName = 'Bus';

    protected $extraRepos = [
        'Document',
        'EbsrSubmission',
        'Licence',
        'BusRegOtherService',
        'TrafficArea',
        'LocalAuthority',
        'BusServiceType'
    ];

    protected $xmlStructureInput;

    protected $busRegInput;

    protected $processedDataInput;

    protected $shortNoticeInput;

    /**
     * @var Result
     */
    protected $result;

    /**
     * @var FileProcessor
     */
    protected $fileProcessor;

    /**
     * @var int
     */
    protected $validPacks = 0;

    /**
     * @var int
     */
    protected $invalidPacks = 0;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mainServiceLocator = $serviceLocator->getServiceLocator();

        $this->xmlStructureInput = $mainServiceLocator->get('EbsrXmlStructure');
        $this->busRegInput = $mainServiceLocator->get('EbsrBusRegInput');
        $this->processedDataInput = $mainServiceLocator->get('EbsrProcessedDataInput');
        $this->shortNoticeInput = $mainServiceLocator->get('EbsrShortNoticeInput');
        $this->fileProcessor = $mainServiceLocator->get(FileProcessorInterface::class);
        $this->result = new Result();

        return parent::createService($serviceLocator);
    }

    /**
     * @param CommandInterface $command
     * @return Result
     * @throws \Exception
     */
    public function handleCommand(CommandInterface $command)
    {
        /** @var ProcessPacksCmd $command */
        $packs = $command->getPacks();

        /** @var OrganisationEntity $organisation */
        $organisation = $this->getCurrentOrganisation();

        foreach ($packs as $packId) {
            /** @var DocumentEntity $doc */
            $doc = $this->getRepo('Document')->fetchById($packId);
            $ebsrSub = $this->createEbsrSubmission($organisation, $doc, $command->getSubmissionType());
            $this->getRepo('EbsrSubmission')->save($ebsrSub);
            $this->result->addId('ebsrSubmission_' . $ebsrSub->getId(), $ebsrSub->getId());

            try {
                $xmlName = $this->fileProcessor->fetchXmlFileNameFromDocumentStore($doc->getIdentifier());
            } catch (\RuntimeException $e) {
                $this->invalidPacks++;
                $this->addErrorMessages($doc, [$e->getMessage()], '');
                $this->setEbsrSubmissionFailed($ebsrSub);

                continue;
            }

            //validate the xml structure
            $xmlDocContext = ['xml_filename' => $xmlName];
            $ebsrDoc = $this->validateInput('xmlStructure', $ebsrSub, $doc, $xmlName, $xmlName, $xmlDocContext);

            if ($ebsrDoc === false) {
                continue;
            }

            $busRegInputContext = [
                'submissionType' => $command->getSubmissionType(),
                'organisation' => $organisation
            ];

            //do some pre-doctrine data processing
            $ebsrData = $this->validateInput('busReg', $ebsrSub, $doc, $xmlName, $ebsrDoc, $busRegInputContext);

            if ($ebsrData === false) {
                continue;
            }

            //we now have xml data we can add to our ebsr submission record
            $ebsrSub = $this->addXmlDataToEbsrSubmission($ebsrSub, $ebsrData);

            //get the parts of the data we need doctrine for
            $ebsrData = $this->getDoctrineInformation($ebsrData);

            /** @var BusRegEntity $previousBusReg */
            $previousBusReg = $this->getRepo()->fetchLatestUsingRegNo($ebsrData['existingRegNo']);

            //we now have the data from doctrine, so validate this additional data
            $processedContext = ['busReg' => $previousBusReg];
            $ebsrData = $this->validateInput('processedData', $ebsrSub, $doc, $xmlName, $ebsrData, $processedContext);

            if ($ebsrData === false) {
                continue;
            }

            //we have valid data, so build a bus reg record
            $busReg = $this->createBusReg($ebsrData, $previousBusReg);

            //we can only validate short notice data once we've created the bus reg
            if (!$this->validateInput('shortNotice', $ebsrSub, $doc, $xmlName, $ebsrData, ['busReg' => $busReg])) {
                continue;
            }

            //short notice has passed validation
            if ($busReg->getIsShortNotice() === 'Y') {
                $busReg->getShortNotice()->fromData($ebsrData['busShortNotice']);
            }

            //update the ebsr submission to show a validated status
            $ebsrSub->updateStatus($this->getRepo()->getRefdataReference(EbsrSubmissionEntity::VALIDATED_STATUS));

            //save the submission and the bus reg
            $this->getRepo('EbsrSubmission')->save($ebsrSub);
            $busReg->setEbsrSubmissions(new ArrayCollection([$ebsrSub]));
            $this->getRepo()->save($busReg);

            //update submission status to processed
            $ebsrSub->updateStatus($this->getRepo()->getRefdataReference(EbsrSubmissionEntity::PROCESSED_STATUS));
            $ebsrSub->setBusReg($busReg);
            $this->getRepo('EbsrSubmission')->save($ebsrSub);

            //trigger side effects (persist docs, txc inbox, create task, request a route map, create fee, send email)
            $sideEffects = $this->getSideEffects($ebsrData, $busReg, dirname($xmlName));
            $this->handleSideEffects($sideEffects);

            $this->validPacks++;

            $this->result->addMessage(
                $doc->getDescription() . '(' . basename($xmlName) . '): file processed successfully'
            );
        }

        $this->result->addId('valid', $this->validPacks);
        $this->result->addId('errors', $this->invalidPacks);

        return $this->result;
    }

    /**
     * @param string $filter
     * @param EbsrSubmissionEntity $ebsrSub
     * @param DocumentEntity $doc
     * @param string $xmlName
     * @param array $value
     * @param array $context
     *
     * @return array|bool
     */
    private function validateInput(
        $filter,
        EbsrSubmissionEntity $ebsrSub,
        DocumentEntity $doc,
        $xmlName,
        $value,
        $context = []
    ) {
        $inputFilter = $filter . 'Input';

        $this->$inputFilter->setValue($value);

        if (!$this->$inputFilter->isValid($context)) {
            $this->invalidPacks++;
            $messages = $this->$inputFilter->getMessages();
            $this->addErrorMessages($doc, $messages, $xmlName);
            $this->setEbsrSubmissionFailed($ebsrSub);

            return false;
        }

        return $this->$inputFilter->getValue();
    }

    /**
     * @param DocumentEntity $doc
     * @param array $messages
     * @param string $xmlName
     * @return Result
     */
    private function addErrorMessages(DocumentEntity $doc, array $messages, $xmlName)
    {
        $filename = '';
        $joinedMessages = strtolower(implode(', ', $messages));

        if (!empty($xmlName)) {
            $filename = ' (' . basename($xmlName) . ')';
        }

        $errorMsg = 'Error with ' . $doc->getDescription() . $filename . ': ' . $joinedMessages . ' - not processed';
        $this->result->addId('error_messages', $errorMsg, true);

        return $this->result;
    }

    /**
     * @param OrganisationEntity $organisation
     * @param DocumentEntity $doc
     * @param $submissionType
     * @return EbsrSubmissionEntity
     * @throws \Dvsa\Olcs\Api\Domain\Exception\RuntimeException
     */
    private function createEbsrSubmission(OrganisationEntity $organisation, DocumentEntity $doc, $submissionType)
    {
        return new EbsrSubmissionEntity(
            $organisation,
            $this->getRepo()->getRefdataReference(EbsrSubmissionEntity::VALIDATING_STATUS),
            $this->getRepo()->getRefdataReference($submissionType),
            $doc,
            new \DateTime()
        );
    }

    /**
     * Add in ebsr submission data after the file has been processed
     *
     * @param EbsrSubmissionEntity $ebsrSub
     * @param array $ebsrData
     * @return EbsrSubmissionEntity
     */
    private function addXmlDataToEbsrSubmission(EbsrSubmissionEntity $ebsrSub, array $ebsrData)
    {
        $ebsrSub->setLicenceNo($ebsrData['licNo']);
        $ebsrSub->setVariationNo($ebsrData['variationNo']);
        $ebsrSub->setRegistrationNo($ebsrData['routeNo']);
        $ebsrSub->setOrganisationEmailAddress($ebsrData['organisationEmail']);

        return $ebsrSub;
    }

    /**
     * @param EbsrSubmissionEntity $ebsrSub
     * @return EbsrSubmissionEntity
     */
    private function setEbsrSubmissionFailed($ebsrSub)
    {
        $ebsrSub->updateStatus($this->getRepo()->getRefdataReference(EbsrSubmissionEntity::FAILED_STATUS));
        $this->getRepo('EbsrSubmission')->save($ebsrSub);
        return $ebsrSub;
    }

    /**
     * Creates the bus registration
     *
     * @param array $ebsrData
     * @param BusRegEntity|array $previousBusReg
     * @return BusRegEntity
     */
    private function createBusReg(array $ebsrData, $previousBusReg)
    {
        //decide what to do based on txcAppType
        switch ($ebsrData['txcAppType']) {
            case 'new':
                $busReg = $this->createNew($ebsrData);
                break;
            case 'cancel':
                $busReg = $this->createVariation($previousBusReg, BusRegEntity::STATUS_CANCEL);
                break;
            default:
                $busReg = $this->createVariation($previousBusReg, BusRegEntity::STATUS_VAR);
        }

        $busReg->fromData($this->prepareBusRegData($ebsrData));
        $busReg->populateShortNotice();
        $this->processServiceNumbers($busReg, $ebsrData['otherServiceNumbers']);

        return $busReg;
    }

    /**
     * Unset any data keys that might clash with the busReg entity fromData method
     *
     * @param array $ebsrData
     * @return array
     */
    private function prepareBusRegData($ebsrData)
    {
        $busRegData = $ebsrData;
        unset($busRegData['documents']);
        unset($busRegData['variationNo']);
        return $busRegData;
    }

    /**
     * @param array $ebsrData
     * @param BusRegEntity $busReg
     * @param string $docPath
     * @return array
     */
    private function getSideEffects(array $ebsrData, BusRegEntity $busReg, $docPath)
    {
        $sideEffects = $this->persistDocuments($ebsrData, $busReg, $docPath);
        $sideEffects[] = $this->createTxcInboxCmd($busReg->getId());
        $sideEffects[] = $this->createTaskCommand($busReg);
        $sideEffects[] = $this->getRequestMapQueueCmd($busReg->getId());

        $busStatus = $busReg->getStatus()->getId();

        if ($busStatus === BusRegEntity::STATUS_NEW || $busStatus === BusRegEntity::STATUS_VAR) {
            $sideEffects[] = CreateBusFeeCmd::create(['id' => $busReg->getId()]);
        }

        /** @var EbsrSubmissionEntity $ebsrSub */
        $ebsrSub = $busReg->getEbsrSubmissions()->first();

        if ($ebsrSub->isDataRefresh()) {
            $sideEffects[] = $this->getEbsrRefreshedEmailCmd($ebsrSub->getId());
        } else {
            $sideEffects[] = $this->getEbsrReceivedEmailCmd($ebsrSub->getId());
        }

        return $sideEffects;
    }

    /**
     * @param array $ebsrData
     * @param BusRegEntity $busReg
     * @param string $docPath
     * @return array
     */
    private function persistDocuments(array $ebsrData, BusRegEntity $busReg, $docPath)
    {
        $sideEffects = [];

        //store any supporting documents
        if (isset($ebsrData['documents'])) {
            foreach ($ebsrData['documents'] as $docName) {
                $path = $docPath . '/' . $docName;
                $sideEffects[] = $this->persistSupportingDoc($path, $busReg, $docName, 'Supporting document');
            }
        }

        //store a new map if present
        if (isset($ebsrData['map'])) {
            $path = $docPath . '/' . $ebsrData['map'];
            $sideEffects[] = $this->persistSupportingDoc($path, $busReg, $ebsrData['map'], 'Schematic map');
        }

        return $sideEffects;
    }

    /**
     * @param string $content
     * @param BusRegEntity $busReg
     * @param string $filename
     * @param string $description
     * @return UploadCmd
     */
    private function persistSupportingDoc($content, BusRegEntity $busReg, $filename, $description)
    {
        $data = [
            'content' => base64_encode(file_get_contents($content)),
            'busReg' => $busReg->getId(),
            'licence' => $busReg->getLicence()->getId(),
            'category' => CategoryEntity::CATEGORY_BUS_REGISTRATION,
            'subCategory' => CategoryEntity::BUS_SUB_CATEGORY_OTHER_DOCUMENTS,
            'filename' => $filename,
            'description' => $description
        ];

        return UploadCmd::create($data);
    }

    /**
     * @param int $busRegId
     * @return CreateTxcInboxCmd
     */
    private function createTxcInboxCmd($busRegId)
    {
        return CreateTxcInboxCmd::create(['id' => $busRegId]);
    }

    /**
     * @param int $busRegId
     * @return RequestMapQueueCmd
     */
    private function getRequestMapQueueCmd($busRegId)
    {
        return RequestMapQueueCmd::create(['id' => $busRegId, 'scale' => 'small']);
    }

    /**
     * @param int $ebsrId
     * @return SendEbsrRefreshedCmd
     */
    private function getEbsrRefreshedEmailCmd($ebsrId)
    {
        return $this->ebsrEmailQueue(SendEbsrRefreshedCmd::class, $ebsrId);
    }

    /**
     * @param int $ebsrId
     * @return SendEbsrReceivedCmd
     */
    private function getEbsrReceivedEmailCmd($ebsrId)
    {
        return $this->ebsrEmailQueue(SendEbsrReceivedCmd::class, $ebsrId);
    }

    /**
     * Adds the ebsr email to the queue
     *
     * @param string $cmdClass
     * @param int $ebsrId
     * @return CreateQueue
     */
    private function ebsrEmailQueue($cmdClass, $ebsrId)
    {
        $options =                     [
            'commandClass' => $cmdClass,
            'commandData' => [
                'id' => $ebsrId
            ],
        ];

        return CreateQueue::create(
            [
                'entityId' => $ebsrId,
                'type' => Queue::TYPE_EMAIL,
                'status' => Queue::STATUS_QUEUED,
                'options' => ZendJson::encode($options)
            ]
        );
    }

    /**
     * @param BusRegEntity $busReg
     * @return CreateTaskCmd
     */
    private function createTaskCommand(BusRegEntity $busReg)
    {
        $submissionType = $busReg->getEbsrSubmissions()->first()->getEbsrSubmissionType();

        if ($submissionType === EbsrSubmissionEntity::DATA_REFRESH_SUBMISSION_TYPE) {
            $description = 'Data refresh created';
        } else {
            $status = $busReg->getStatus()->getId();

            switch ($status) {
                case BusRegEntity::STATUS_CANCEL:
                    $state = 'cancellation';
                    break;
                case BusRegEntity::STATUS_VAR:
                    $state = 'variation';
                    break;
                default:
                    $state = 'application';
            }

            $description = 'New ' . $state . ' created';
        }

        $data = [
            'category' => TaskEntity::CATEGORY_BUS,
            'subCategory' => TaskEntity::SUBCATEGORY_EBSR,
            'description' => $description . ': [' . $busReg->getRegNo() . ']',
            'actionDate' => date('Y-m-d H:i:s'),
            'assignedToUser' => $this->getCurrentUser()->getId(),
            'assignedToTeam' => 6,
            'busReg' => $busReg->getId(),
            'licence' => $busReg->getLicence()->getId(),
        ];

        return CreateTaskCmd::create($data);
    }

    /**
     * Create a new bus reg
     *
     * @param array $ebsrData
     * @throws Exception\ForbiddenException
     * @return BusRegEntity
     */
    private function createNew(array $ebsrData)
    {
        /** @var LicenceEntity $licence */
        $licence = $this->getRepo('Licence')->fetchByLicNo($ebsrData['licNo']);
        $refDataStatus = $this->getRepo()->getRefdataReference(BusRegEntity::STATUS_NEW);

        $newBusReg = BusRegEntity::createNew(
            $licence,
            $refDataStatus,
            $refDataStatus,
            $ebsrData['subsidised'],
            $ebsrData['busNoticePeriod']
        );

        //quick fix: overwrite the reg no that createNew produced, with the one from EBSR - need to move this logic
        $newBusReg->setRegNo($licence->getLicNo() . '/' . $ebsrData['routeNo']);

        return $newBusReg;
    }

    /**
     * @param BusRegEntity $busReg
     * @param string $status
     * @return BusRegEntity
     */
    private function createVariation(BusRegEntity $busReg, $status)
    {
        $refDataStatus = $this->getRepo()->getRefdataReference($status);
        return $busReg->createVariation($refDataStatus, $refDataStatus);
    }

    /**
     * Ebsr information which couldn't be processed using the pre-migration filters, as we needed Doctrine
     *
     * @param array $ebsrData
     * @return array
     */
    private function getDoctrineInformation(array $ebsrData)
    {
        $ebsrData['subsidised'] = $this->getRepo()->getRefdataReference($ebsrData['subsidised']);
        $ebsrData['naptanAuthorities'] = $this->processNaptan($ebsrData['naptan']);
        $ebsrData['localAuthoritys'] = $this->processLocalAuthority($ebsrData['localAuthorities']);
        $ebsrData['trafficAreas'] = $this->processTrafficAreas($ebsrData['trafficAreas'], $ebsrData['localAuthoritys']);
        $ebsrData['busServiceTypes'] = $this->processServiceTypes($ebsrData['serviceClassifications']);
        $ebsrData['busNoticePeriod'] = $this->getRepo()->getReference(
            BusNoticePeriodEntity::class, $ebsrData['busNoticePeriod']
        );

        return $ebsrData;
    }

    /**
     * Returns collection of service types.
     *
     * @param array $serviceTypes
     * @return ArrayCollection
     */
    private function processServiceTypes(array $serviceTypes)
    {
        $collection = new ArrayCollection();

        if (!empty($serviceTypes)) {
            $serviceTypeArray = array_keys($serviceTypes);

            $serviceTypeList = $this->getRepo('BusServiceType')->fetchByTxcName($serviceTypeArray);

            /** @var BusServiceTypeEntity $serviceType */
            foreach ($serviceTypeList as $serviceType) {
                $collection->add($serviceType);
            }
        }

        return $collection;
    }

    /**
     * @param BusRegEntity $busReg
     * @param array $serviceNumbers
     * @return BusRegEntity
     */
    private function processServiceNumbers(BusRegEntity $busReg, array $serviceNumbers)
    {
        //first make sure we have an empty array collection
        $busReg->setOtherServices(new ArrayCollection());

        foreach ($serviceNumbers as $number) {
            $busReg->addOtherServiceNumber($number);
        }

        return $busReg;
    }

    /**
     * Returns collection of local authorities.
     *
     * @param array $localAuthority
     * @return ArrayCollection
     */
    private function processLocalAuthority(array $localAuthority)
    {
        $collection = new ArrayCollection();

        if (!empty($localAuthority)) {
            $laList = $this->getRepo('LocalAuthority')->fetchByTxcName($localAuthority);

            /** @var LocalAuthorityEntity $la */
            foreach ($laList as $la) {
                $collection->add($la);
            }
        }

        return $collection;
    }

    /**
     * Returns collection of local authorities based on the naptan codes.
     *
     * @param array $naptan
     * @return ArrayCollection
     */
    private function processNaptan(array $naptan)
    {
        $collection = new ArrayCollection();

        if (!empty($naptan)) {
            $laList = $this->getRepo('LocalAuthority')->fetchByNaptan($naptan);

            /** @var LocalAuthorityEntity $la */
            foreach ($laList as $la) {
                $collection->add($la);
            }
        }

        return $collection;
    }

    /**
     * Returns collection of traffic areas.
     *
     * @param array $trafficAreas
     * @param ArrayCollection $localAuthorities
     * @return ArrayCollection
     */
    private function processTrafficAreas(array $trafficAreas, ArrayCollection $localAuthorities)
    {
        $collection = new ArrayCollection();

        if (!empty($trafficAreas)) {
            $taList = $this->getRepo('TrafficArea')->fetchByTxcName($trafficAreas);

            /** @var TrafficAreaEntity $ta */
            foreach ($taList as $ta) {
                $collection->add($ta);
            }
        }

        /**
         * @var LocalAuthorityEntity $la
         */
        foreach ($localAuthorities as $la) {
            $ta = $la->getTrafficArea();

            if (!$collection->contains($ta)) {
                $collection->add($ta);
            }
        }

        return $collection;
    }
}
