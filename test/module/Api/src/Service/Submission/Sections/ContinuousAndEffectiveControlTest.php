<?php

namespace Dvsa\OlcsTest\Api\Service\Submission\Sections;

/**
 * Class ContinuousAndEffectiveControlTest
 * @author Shaun Lizzio <shaun@valtech.co.uk>
 */
class ContinuousAndEffectiveControlTest extends SubmissionSectionTest
{
    protected $submissionSection = '\Dvsa\Olcs\Api\Service\Submission\Sections\ContinuousAndEffectiveControl';

    /**
     * Filter provider
     *
     * @return array
     */
    public function sectionTestProvider()
    {
        $case = $this->getCase();

        $expectedResult = ['data' => []];

        return [
            [$case, $expectedResult],
        ];
    }
}
