<?php

namespace Dvsa\Olcs\Api\Service\Submission\Sections;

use Dvsa\Olcs\Api\Entity\Cases\Cases as CasesEntity;

/**
 * Class ApplicantsComments
 * @package Dvsa\Olcs\Api\Service\Submission\Sections
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
final class ApplicantsComments extends AbstractSection
{
    public function generateSection(CasesEntity $case)
    {
        $defaultText = "<p>TE REPORT:</p>
<p>SIZE:</p>
<p>ACCESS/EGRESS/MANOEUVRE:</p>
<p>VISIBILITY:</p>
<p>TE COMMENTS:</p>
<p>TE CONCLUSIONS:</p>";

        return ['data' => ['text' => $defaultText]];
    }
}
