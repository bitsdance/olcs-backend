<?php

/**
 * Variation Financial Evidence Review Service
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Snapshot\Service\Snapshots\ApplicationReview\Section;

/**
 * Variation Financial Evidence Review Service
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class VariationFinancialEvidenceReviewService extends AbstractReviewService
{
    /** @var ApplicationFinancialEvidenceReviewService */
    private $applicationFinancialEvidenceReviewService;

    /**
     * Create service instance
     *
     * @param AbstractReviewServiceServices $abstractReviewServiceServices
     * @param ApplicationFinancialEvidenceReviewService $applicationFinancialEvidenceReviewService
     *
     * @return VariationFinancialEvidenceReviewService
     */
    public function __construct(
        AbstractReviewServiceServices $abstractReviewServiceServices,
        ApplicationFinancialEvidenceReviewService $applicationFinancialEvidenceReviewService
    ) {
        parent::__construct($abstractReviewServiceServices);
        $this->applicationFinancialEvidenceReviewService = $applicationFinancialEvidenceReviewService;
    }

    /**
     * Format the readonly config from the given data
     *
     * @param array $data
     * @return array
     */
    public function getConfigFromData(array $data = array())
    {
        return $this->applicationFinancialEvidenceReviewService->getConfigFromData($data);
    }
}
