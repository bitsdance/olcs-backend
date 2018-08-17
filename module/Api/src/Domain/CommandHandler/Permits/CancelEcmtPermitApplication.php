<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\Permits;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;

use Dvsa\Olcs\Api\Entity\Permits\EcmtPermitApplication;
use Dvsa\Olcs\Api\Entity\System\RefData;

use Dvsa\Olcs\Transfer\Command\CommandInterface;

/**
 * Create an ECMT Permit application
 *
 * @author Jason de Jonge
 */
final class CancelEcmtPermitApplication extends AbstractCommandHandler
{
    protected $repoServiceName = 'EcmtPermitApplication';

    /**
     * Handle command
     *
     * @param CommandInterface $command command
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $result = new Result();

        $application = $this->getRepo()->fetchById($command->getId());

        /** @var EcmtPermitApplication $application */
        $application->setStatus($this->getRepo()->getRefdataReference(EcmtPermitApplication::STATUS_CANCELLED));

        $this->getRepo()->save($application);

        $result->addId('ecmtPermitApplication', $application->getId());

        return $result;
    }
}
