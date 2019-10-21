<?php

namespace Dvsa\Olcs\Api\Domain\QueryHandler\IrhpApplication;

use Dvsa\Olcs\Api\Domain\QueryHandler\AbstractQueryHandler;
use Dvsa\Olcs\Api\Domain\ToggleAwareTrait;
use Dvsa\Olcs\Api\Domain\ToggleRequiredInterface;
use Dvsa\Olcs\Api\Entity\Permits\IrhpApplication;
use Dvsa\Olcs\Api\Entity\System\FeatureToggle;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Exception;

/**
 * Retrieve IRHP application by id
 */
final class ById extends AbstractQueryHandler implements ToggleRequiredInterface
{
    use ToggleAwareTrait;

    protected $toggleConfig = [FeatureToggle::BACKEND_PERMITS];
    protected $repoServiceName = 'IrhpApplication';
    protected $bundle = [
        'licence' => ['trafficArea', 'organisation'],
        'irhpPermitType' => ['name'],
        'fees' => ['feeType' => ['feeType'], 'feeStatus'],
        'irhpPermitApplications' => ['irhpPermitWindow' => ['irhpPermitStock' => ['country', 'irhpPermitType']]],
        'sectors',
    ];

    /**
     * Handle query
     *
     * @param QueryInterface $query query
     *
     * @return array
     */
    public function handleQuery(QueryInterface $query)
    {
        /** @var IrhpApplication $irhpApplication */
        $irhpApplication = $this->getRepo()->fetchUsingId($query);

        try {
            $totalPermitsRequired = $irhpApplication->calculateTotalPermitsRequired();
            $totalPermitsAwarded = $irhpApplication->getPermitsAwarded();
        } catch (Exception $e) {
            $totalPermitsRequired = null;
            $totalPermitsAwarded = null;
        }

        return $this->result(
            $irhpApplication,
            $this->bundle,
            [
                'canViewCandidatePermits' => $irhpApplication->canViewCandidatePermits(),
                'totalPermitsAwarded' => $totalPermitsAwarded,
                'totalPermitsRequired' => $totalPermitsRequired,
            ]
        );
    }
}
