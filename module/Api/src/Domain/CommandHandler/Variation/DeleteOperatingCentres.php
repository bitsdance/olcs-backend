<?php

/**
 * Delete Operating Centres
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Variation;

use Doctrine\Common\Collections\Criteria;
use Dvsa\Olcs\Api\Domain\Command\Application\UpdateApplicationCompletion as UpdateApplicationCompletionCmd;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\Exception\BadRequestException;
use Dvsa\Olcs\Api\Domain\Util\EntityCloner;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\Olcs\Api\Entity\Application\ApplicationOperatingCentre;
use Dvsa\Olcs\Api\Entity\Licence\LicenceOperatingCentre;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Transfer\Command\Variation\DeleteOperatingCentre as Cmd;

/**
 * Delete Operating Centres
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
final class DeleteOperatingCentres extends AbstractCommandHandler implements TransactionedInterface
{
    protected $repoServiceName = 'Application';

    protected $extraRepos = ['ApplicationOperatingCentre'];

    /**
     * @param Cmd $command
     * @return \Dvsa\Olcs\Api\Domain\Command\Result
     */
    public function handleCommand(CommandInterface $command)
    {
        /** @var ApplicationEntity $application */
        $application = $this->getRepo()->fetchById($command->getApplication());

        if (!$this->canDeleteRecord($application, $command->getId())) {
            throw new BadRequestException('could-not-remove-message');
        }

        list($type, $id) = $this->splitTypeAndId($command->getId());

        if ($type === 'A') {
            $this->result->addMessage('Removed application operating centre delta record');
            $this->getRepo('ApplicationOperatingCentre')->delete(
                $this->getRepo()->getReference(ApplicationOperatingCentre::class, $id)
            );
        } else {
            $this->variationDelete($id, $application);
        }

        $completionData = ['id' => $application->getId(), 'section' => 'operatingCentres'];
        $this->result->merge($this->handleSideEffect(UpdateApplicationCompletionCmd::create($completionData)));

        return $this->result;
    }

    public function variationDelete($id, ApplicationEntity $application)
    {
        $locRecord = $this->getRepo()->getReference(LicenceOperatingCentre::class, $id);

        /** @var ApplicationOperatingCentre $aocRecord */
        $aocRecord = EntityCloner::cloneEntityInto($locRecord, ApplicationOperatingCentre::class);

        $aocRecord->setAction('D');
        $aocRecord->setApplication($application);

        $this->getRepo('ApplicationOperatingCentre')->save($aocRecord);

        $this->result->addMessage('Created application operating centre delta record');
    }

    protected function canDeleteRecord(ApplicationEntity $application, $ref)
    {
        list($type, $id) = $this->splitTypeAndId($ref);

        // If we have an application operating centre record
        if ($type === 'A') {
            /** @var ApplicationOperatingCentre $record */
            $record = $this->getRepo()->getReference(ApplicationOperatingCentre::class, $id);

            return in_array($record->getAction(), ['U', 'A']);
        }

        /** @var LicenceOperatingCentre $record */
        $record = $this->getRepo()->getReference(LicenceOperatingCentre::class, $id);

        $oc = $record->getOperatingCentre();

        $criteria = Criteria::create();
        $criteria->andWhere(
            $criteria->expr()->eq('operatingCentre', $oc)
        );

        $aocRecords = $application->getOperatingCentres()->matching($criteria);

        return $aocRecords->count() < 1;
    }

    protected function splitTypeAndId($ref)
    {
        $type = substr($ref, 0, 1);

        if (is_numeric($type)) {
            return [null, $ref];
        }

        $id = (int)substr($ref, 1);

        return [$type, $id];
    }
}
