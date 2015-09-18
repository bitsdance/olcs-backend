<?php

/**
 * UserSelfserveTest
 */
namespace Dvsa\OlcsTest\Api\Domain\QueryHandler\User;

use Dvsa\Olcs\Api\Domain\QueryHandler\User\UserSelfserve as QueryHandler;
use Dvsa\Olcs\Api\Domain\Repository\User as Repo;
use Dvsa\Olcs\Transfer\Query\User\UserSelfserve as Query;
use Dvsa\OlcsTest\Api\Domain\QueryHandler\QueryHandlerTestCase;
use Mockery as m;

/**
 * UserSelfserveTest
 */
class UserSelfserveTest extends QueryHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new QueryHandler();
        $this->mockRepo('User', Repo::class);

        parent::setUp();
    }

    public function testHandleQuery()
    {
        $query = Query::create(['QUERY']);

        $mockUser = m::mock(\Dvsa\Olcs\Api\Entity\User\User::class);
        $mockUser->shouldReceive('serialize')->once()->andReturn(['foo' => 'bar']);

        $this->repoMap['User']->shouldReceive('fetchUsingId')->with($query)->andReturn($mockUser);

        $result = $this->sut->handleQuery($query)->serialize();

        $this->assertSame(['foo' => 'bar'], $result);
    }
}
