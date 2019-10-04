<?php

namespace Dvsa\Olcs\Cli\Domain\CommandHandler\Permits;

use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\ToggleAwareTrait;
use Dvsa\Olcs\Api\Domain\ToggleRequiredInterface;
use Dvsa\Olcs\Api\Entity\System\FeatureToggle;
use Dvsa\Olcs\Api\Service\Permits\Scoring\ScoringQueryProxy;
use Dvsa\Olcs\Api\Service\Permits\Scoring\SuccessfulCandidatePermitsFacade;
use Dvsa\Olcs\Cli\Domain\Command\MarkSuccessfulDaPermitApplications as MarkSuccessfulDaPermitApplicationsCommand;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Set successful permit applications for each devolved administration
 * See https://wiki.i-env.net/display/olcs/Batch+Process%3A+Identify+Successful+Permit+Applications+with+Restricted+Countries
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class MarkSuccessfulDaPermitApplications extends ScoringCommandHandler implements TransactionedInterface, ToggleRequiredInterface
{
    use ToggleAwareTrait;

    protected $toggleConfig = [FeatureToggle::BACKEND_PERMITS];

    protected $repoServiceName = 'IrhpPermitJurisdictionQuota';

    /** @var ScoringQueryProxy */
    private $scoringQueryProxy;

    /** @var SuccessfulCandidatePermitsFacade */
    private $successfulCandidatePermitsFacade;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator Service Manager
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mainServiceLocator = $serviceLocator->getServiceLocator();

        $this->scoringQueryProxy = $mainServiceLocator->get('PermitsScoringScoringQueryProxy');

        $this->successfulCandidatePermitsFacade = $mainServiceLocator->get(
            'PermitsScoringSuccessfulCandidatePermitsFacade'
        );

        return parent::createService($serviceLocator);
    }

    /**
     * Handle command
     *
     * @param CommandInterface|MarkSuccessfulDaPermitApplicationsCommand $command command
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $this->profileMessage('mark successful da permit applications...');

        $stockId = $command->getStockId();
        $daQuotas = $this->getRepo()->fetchByNonZeroQuota($stockId);

        $this->result->addMessage('STEP 2c:');
        $this->result->addMessage('  DAs associated with stock where quota > 0: ' . count($daQuotas));

        $totalSuccessfulCandidatePermits = 0;

        foreach ($daQuotas as $daQuota) {
            $daSuccessCount = $this->scoringQueryProxy->getSuccessfulDaCountInScope(
                $stockId,
                $daQuota['jurisdictionId']
            );

            $daRemainingQuota = $daQuota['quotaNumber'] - $daSuccessCount;

            $this->result->addMessage('    DA with id ' . $daQuota['jurisdictionId'] . ':');
            $this->result->addMessage('      Derived values:');
            $this->result->addMessage('      - #DAQuota:          ' . $daQuota['quotaNumber']);
            $this->result->addMessage('      - #DASuccessCount:   ' . $daSuccessCount);
            $this->result->addMessage('      - #DARemainingQuota: ' . $daRemainingQuota);

            if ($daRemainingQuota > 0) {
                $daCandidatePermits = $this->scoringQueryProxy->getUnsuccessfulScoreOrderedInScope(
                    $stockId,
                    $daQuota['jurisdictionId']
                );

                $successfulCandidatePermits = $this->successfulCandidatePermitsFacade->generate(
                    $daCandidatePermits,
                    $command->getStockId(),
                    $daRemainingQuota
                );

                $this->result->addMessage('      Permits requesting this DA: ' . count($daCandidatePermits));
                $this->result->addMessage('      - adjusted for quota: ' . count($successfulCandidatePermits));
                $this->successfulCandidatePermitsFacade->log($successfulCandidatePermits, $this->result);

                $this->successfulCandidatePermitsFacade->write($successfulCandidatePermits);
                $totalSuccessfulCandidatePermits += count($successfulCandidatePermits);
            } else {
                $this->result->addMessage('      #DARemainingQuota < 1 - nothing to do');
            }
        }

        $this->result->addMessage('  ' . $totalSuccessfulCandidatePermits . ' permits have been marked as successful');
        return $this->result;
    }
}
