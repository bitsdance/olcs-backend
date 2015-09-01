<?php

/**
 * Update Irfo Gv Permit
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Irfo;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Entity\Irfo\IrfoGvPermitType;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Doctrine\ORM\Query;

/**
 * Update Irfo Gv Permit
 */
final class UpdateIrfoGvPermit extends AbstractCommandHandler
{
    protected $repoServiceName = 'IrfoGvPermit';

    public function handleCommand(CommandInterface $command)
    {
        $irfoGvPermit = $this->getRepo()->fetchUsingId($command, Query::HYDRATE_OBJECT, $command->getVersion());

        $irfoGvPermit->update(
            $this->getRepo()->getReference(IrfoGvPermitType::class, $command->getIrfoGvPermitType()),
            $command->getYearRequired(),
            new \DateTime($command->getInForceDate()),
            new \DateTime($command->getExpiryDate()),
            $command->getNoOfCopies(),
            $command->getIsFeeExempt(),
            $command->getExemptionDetails()
        );

        $this->getRepo()->save($irfoGvPermit);

        $result = new Result();
        $result->addId('irfoGvPermit', $irfoGvPermit->getId());
        $result->addMessage('IRFO GV Permit updated successfully');

        return $result;
    }
}
