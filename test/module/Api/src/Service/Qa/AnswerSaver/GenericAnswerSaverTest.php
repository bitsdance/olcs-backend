<?php

namespace Dvsa\OlcsTest\Api\Service\Qa\AnswerSaver;

use Dvsa\Olcs\Api\Entity\Generic\ApplicationStep;
use Dvsa\Olcs\Api\Entity\Permits\IrhpApplication;
use Dvsa\Olcs\Api\Service\Qa\AnswerSaver\GenericAnswerSaver;
use Dvsa\Olcs\Api\Service\Qa\AnswerSaver\GenericAnswerWriter;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * GenericAnswerSaverTest
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class GenericAnswerSaverTest extends MockeryTestCase
{
    public function testSave()
    {
        $fieldsetName = 'fields456';
        $qaElementValue = 'qaElementValue';

        $postData = [
            $fieldsetName => [
                'qaElement' => 'qaElementValue'
            ]
        ];

        $applicationStep = m::mock(ApplicationStep::class);
        $applicationStep->shouldReceive('getFieldsetName')
            ->andReturn($fieldsetName);

        $irhpApplication = m::mock(IrhpApplication::class);

        $genericAnswerWriter = m::mock(GenericAnswerWriter::class);
        $genericAnswerWriter->shouldReceive('write')
            ->with($applicationStep, $irhpApplication, $qaElementValue);

        $genericAnswerSaver = new GenericAnswerSaver($genericAnswerWriter);
        $genericAnswerSaver->save($applicationStep, $irhpApplication, $postData);
    }
}
