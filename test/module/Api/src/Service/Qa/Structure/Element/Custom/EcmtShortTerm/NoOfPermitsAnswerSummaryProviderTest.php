<?php

namespace Dvsa\OlcsTest\Api\Service\Qa\Structure\Element\Custom\EcmtShortTerm;

use Dvsa\Olcs\Api\Entity\Generic\ApplicationStep as ApplicationStepEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpApplication as IrhpApplicationEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitApplication as IrhpPermitApplicationEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitStock as IrhpPermitStockEntity;
use Dvsa\Olcs\Api\Service\Qa\Structure\Element\Custom\EcmtShortTerm\NoOfPermitsAnswerSummaryProvider;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * NoOfPermitsAnswerSummaryProviderTest
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class NoOfPermitsAnswerSummaryProviderTest extends MockeryTestCase
{
    private $noOfPermitsAnswerSummaryProvider;

    public function setUp()
    {
        $this->noOfPermitsAnswerSummaryProvider = new NoOfPermitsAnswerSummaryProvider();
    }

    public function testGetTemplateName()
    {
        $this->assertEquals(
            'ecmt-short-term-no-of-permits',
            $this->noOfPermitsAnswerSummaryProvider->getTemplateName()
        );
    }

    /**
     * @dataProvider dpSnapshot
     */
    public function testGetTemplateVariables($isSnapshot)
    {
        $periodNameKey = 'period.name.key';
        $requiredEuro5 = 5;
        $requiredEuro6 = 7;

        $applicationStepEntity = m::mock(ApplicationStepEntity::class);

        $irhpPermitStockEntity = m::mock(IrhpPermitStockEntity::class);
        $irhpPermitStockEntity->shouldReceive('getPeriodNameKey')
            ->withNoArgs()
            ->andReturn($periodNameKey);

        $irhpPermitApplicationEntity = m::mock(IrhpPermitApplicationEntity::class);
        $irhpPermitApplicationEntity->shouldReceive('getIrhpPermitWindow->getIrhpPermitStock')
            ->withNoArgs()
            ->andReturn($irhpPermitStockEntity);
        $irhpPermitApplicationEntity->shouldReceive('getRequiredEuro5')
            ->withNoArgs()
            ->andReturn($requiredEuro5);
        $irhpPermitApplicationEntity->shouldReceive('getRequiredEuro6')
            ->withNoArgs()
            ->andReturn($requiredEuro6);

        $irhpApplicationEntity = m::mock(IrhpApplicationEntity::class);
        $irhpApplicationEntity->shouldReceive('getFirstIrhpPermitApplication')
            ->withNoArgs()
            ->andReturn($irhpPermitApplicationEntity);

        $expectedTemplateVariables = [
            'periodNameKey' => $periodNameKey,
            'emissionsCategories' => [
                [
                    'key' => 'qanda.common.no-of-permits.emissions-category.euro5',
                    'count' => $requiredEuro5
                ],
                [
                    'key' => 'qanda.common.no-of-permits.emissions-category.euro6',
                    'count' => $requiredEuro6
                ]
            ]
        ];

        $templateVariables = $this->noOfPermitsAnswerSummaryProvider->getTemplateVariables(
            $applicationStepEntity,
            $irhpApplicationEntity,
            $isSnapshot
        );

        $this->assertEquals($expectedTemplateVariables, $templateVariables);
    }

    public function dpSnapshot()
    {
        return [
            [true],
            [false]
        ];
    }
}
