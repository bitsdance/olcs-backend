<?php

namespace Dvsa\OlcsTest\Api\Service\Document\Bookmark;

use Dvsa\Olcs\Api\Domain\Query\Bookmark\LicenceBundle;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation;
use Dvsa\Olcs\Api\Service\Document\Bookmark\Stlcompanyorpartnerparagraph as Sut;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;

/**
 * StlcompanyorpartnerparagraphTest
 */
class StlcompanyorpartnerparagraphTest extends TestCase
{
    public function testGetQuery()
    {
        $bookmark = new Sut();
        $query = $bookmark->getQuery(['licence' => 123]);

        $this->assertInstanceOf(LicenceBundle::class, $query);
        $this->assertSame(123, $query->getId());
        $this->assertSame(['organisation' => ['type']], $query->getBundle());
    }

    public function testRenderLtd()
    {
        $mockParser = m::mock();
        $mockParser->shouldReceive('getFileExtension')->with()->once()->andReturn('rtf');

        $bookmark = new Sut();
        $bookmark->setParser($mockParser);
        $bookmark->setData(['organisation' => ['type' => ['id' => Organisation::ORG_TYPE_REGISTERED_COMPANY]]]);

        $this->assertStringStartsWith('It is important that a director of the company (who', $bookmark->render());
    }

    /**
     * @dataProvider testRenderPartnershipsDataProvider
     */
    public function testRenderPartnerships($organisationTypeId)
    {
        $mockParser = m::mock();
        $mockParser->shouldReceive('getFileExtension')->with()->once()->andReturn('rtf');

        $bookmark = new Sut();
        $bookmark->setParser($mockParser);
        $bookmark->setData(['organisation' => ['type' => ['id' => $organisationTypeId]]]);

        $this->assertStringStartsWith('It is important that a partner who can speak and', $bookmark->render());
    }

    public function testRenderPartnershipsDataProvider()
    {
        return [
            [Organisation::ORG_TYPE_PARTNERSHIP],
            [Organisation::ORG_TYPE_LLP],
        ];
    }

    /**
     * @dataProvider testRenderOthersDataProvider
     */
    public function testRenderOthers($organisationTypeId)
    {
        $bookmark = new Sut();
        $bookmark->setData(['organisation' => ['type' => ['id' => $organisationTypeId]]]);

        $this->assertNull($bookmark->render());
    }

    public function testRenderOthersDataProvider()
    {
        return [
            [Organisation::ORG_TYPE_SOLE_TRADER],
            [Organisation::ORG_TYPE_IRFO],
            [Organisation::ORG_TYPE_OTHER],
            'invalid organisation type' => [0],
        ];
    }
}
