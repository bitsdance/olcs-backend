<?php

/**
 * Process Duplicate Vehicle Warning
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Vehicle;

use Dvsa\Olcs\Api\Domain\Command\Document\CreateDocumentSpecific;
use Dvsa\Olcs\Api\Domain\Command\PrintScheduler\Enqueue;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\DocumentGeneratorAwareInterface;
use Dvsa\Olcs\Api\Domain\DocumentGeneratorAwareTrait;
use Dvsa\Olcs\Api\Domain\Util\DateTime\DateTime;
use Dvsa\Olcs\Api\Entity\System\Category;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Entity\Licence\LicenceVehicle;

/**
 * Process Duplicate Vehicle Warning
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
final class ProcessDuplicateVehicleWarning extends AbstractCommandHandler implements
    TransactionedInterface,
    DocumentGeneratorAwareInterface
{
    use DocumentGeneratorAwareTrait;

    protected $repoServiceName = 'LicenceVehicle';

    public function handleCommand(CommandInterface $command)
    {
        /** @var LicenceVehicle $licenceVehicle */
        $licenceVehicle = $this->getRepo()->fetchUsingId($command);

        $query = [
            'licence' => $licenceVehicle->getLicence()->getId(),
            'vehicle' => $licenceVehicle->getVehicle()->getId()
        ];
        $storedFile = $this->getDocumentGenerator()->generateAndStore('GV_Duplicate_vehicle_letter', $query);

        $description = 'Duplicate vehicle letter';

        $data = [
            'identifier'  => $storedFile->getIdentifier(),
            'description' => $description,
            'filename'    => str_replace(' ', '_', $description) . '.rtf',
            'licence'     => $licenceVehicle->getLicence()->getId(),
            'category'    => Category::CATEGORY_LICENSING,
            'subCategory' => Category::DOC_SUB_CATEGORY_OTHER_DOCUMENTS,
            'isReadOnly'  => true,
            'isExternal'  => false
        ];

        $this->result->merge($this->handleSideEffect(CreateDocumentSpecific::create($data)));

        $data = [
            'fileIdentifier' => $storedFile->getIdentifier(),
            'jobName' => $description
        ];
        $this->result->merge($this->handleSideEffect(Enqueue::create($data)));

        $licenceVehicle->setWarningLetterSentDate(new DateTime());
        $this->getRepo()->save($licenceVehicle);

        $this->result->addMessage('Licence vehicle ID: ' . $licenceVehicle->getId() . ' duplication letter sent');

        return $this->result;
    }
}
