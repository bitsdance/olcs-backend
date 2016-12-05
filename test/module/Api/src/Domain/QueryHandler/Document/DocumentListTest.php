<?php

namespace Dvsa\OlcsTest\Api\Domain\QueryHandler\Document;

use Mockery as m;
use Dvsa\Olcs\Api\Domain\QueryHandler\Document\DocumentList;
use Dvsa\OlcsTest\Api\Domain\QueryHandler\QueryHandlerTestCase;
use Dvsa\Olcs\Api\Domain\Repository\DocumentSearchView as Repo;
use Dvsa\Olcs\Transfer\Query\Document\DocumentList as Qry;
use Dvsa\Olcs\Api\Domain\QueryHandler\BundleSerializableInterface;
use Doctrine\ORM\Query;

/**
 * @covers \Dvsa\Olcs\Api\Domain\QueryHandler\Document\DocumentList
 */
class DocumentListTest extends QueryHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new DocumentList();
        $this->mockRepo('DocumentSearchView', Repo::class);

        parent::setUp();
    }

    public function testHandleQuery()
    {
        $query = Qry::create([]);

        $mockDocument = m::mock(BundleSerializableInterface::class)
            ->shouldReceive('serialize')
            ->andReturn(['foo' => 'bar'])
            ->once()
            ->getMock();

        $this->repoMap['DocumentSearchView']
            ->shouldReceive('fetchList')->once()->with($query, Query::HYDRATE_OBJECT)->andReturn([$mockDocument])
            ->shouldReceive('fetchCount')->once()->with($query)->andReturn(888)
            ->shouldReceive('hasRows')->once()->andReturnUsing(
                function (Qry $query) {
                    static::assertNull($query->getCategory());
                    static::assertEquals([], $query->getDocumentSubCategory());
                    static::assertNull($query->getIsExternal());
                    static::assertNull($query->getShowDocs());

                    return 999;
                }
            );

        $this->assertEquals(
            [
                'result' => [['foo' => 'bar']],
                'count' => 888,
                'count-unfiltered' => 999,
            ],
            $this->sut->handleQuery($query)
        );
    }
}
