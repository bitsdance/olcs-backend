<?php

namespace Dvsa\OlcsTest\Api\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Dvsa\Olcs\Api\Entity;
use Dvsa\Olcs\Api\Listener\OlcsEntityListener;
use Dvsa\OlcsTest\Api\Listener\Stub\EntityStub;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Laminas\ServiceManager\ServiceLocatorInterface;
use ZfcRbac\Service\AuthorizationService;
use Dvsa\Olcs\Api\Domain\Repository\User as UserRepo;
use Dvsa\Olcs\Api\Rbac\IdentityProviderInterface;
use Dvsa\Olcs\Api\Entity\User\User as UserEntity;

/**
 * @covers \Dvsa\Olcs\Api\Listener\OlcsEntityListener
 */
class OlcsEntityListenerTest extends MockeryTestCase
{
    /** @var  OlcsEntityListener */
    private $sut;

    /** @var  m\MockInterface|ServiceLocatorInterface */
    private $mockSl;
    /** @var  m\MockInterface */
    private $mockAuth;

    /** @var  m\MockInterface */
    private $mockEm;
    /** @var  m\MockInterface */
    private $mockUow;
    /** @var  m\MockInterface */
    private $mockMeta;
    /** @var  UserRepo */
    private $mockUserRepo;
    /** @var  m\MockInterface */
    private $mockRepoManager;
    /** @var  IdentityProviderInterface */
    private $mockIdentityProvider;

    public function setUp(): void
    {
        $this->mockMeta = m::mock(\Doctrine\ORM\Mapping\ClassMetadata::class);
        $this->mockUow = m::mock(\Doctrine\ORM\UnitOfWork::class);

        $this->mockEm = m::mock(\Doctrine\ORM\EntityManager::class);
        $this->mockEm->shouldReceive('getUnitOfWork')->atMost(1)->andReturn($this->mockUow);

        $this->mockAuth = m::mock(AuthorizationService::class);

        $this->mockUserRepo = m::mock(\Dvsa\Olcs\Api\Domain\Repository\User::class);

        $this->mockRepoManager = m::mock();
        $this->mockRepoManager->shouldReceive('get')
            ->with('User')
            ->andReturn($this->mockUserRepo);

        $this->mockIdentityProvider = m::mock(IdentityProviderInterface::class);

        $this->mockSl = m::mock(ServiceLocatorInterface::class);
        $this->mockSl
            ->shouldReceive('get')
            ->andReturnUsing(
                function ($key) {
                    $map = [
                        AuthorizationService::class => $this->mockAuth,
                        'RepositoryServiceManager' => $this->mockRepoManager,
                        IdentityProviderInterface::class => $this->mockIdentityProvider
                    ];

                    return $map[$key];
                }
            );

        $this->sut = (new OlcsEntityListener())->createService($this->mockSl);
    }

    public function testGetSubscribedEvents()
    {
        static::assertEquals(['preSoftDelete'], $this->sut->getSubscribedEvents());
    }

    public function testUpdateFieldMethodNotExists()
    {
        $mockEntity = m::mock();

        $this->mockAuth->shouldReceive('getIdentity->getUser')->andReturn(m::mock());

        $this->mockMeta
            ->shouldReceive('getReflectionProperty')->never();

        $this->mockEm
            ->shouldReceive('getClassMetadata')->once()->andReturn($this->mockMeta)
            ->shouldReceive('persist')->never();

        $this->mockUow
            ->shouldReceive('scheduleExtraUpdate')->never();

        $this->mockIdentityProvider
            ->shouldReceive('getMasqueradedAsSystemUser')
            ->andReturn(false)
            ->once()
            ->getMock();

        //  call
        $lifecycleEvent = new LifecycleEventArgs($mockEntity, $this->mockEm);
        $this->sut->preSoftDelete($lifecycleEvent);
    }

    public function testUpdateFieldNotNotify()
    {
        $mockEntity = m::mock(Entity\Note\Note::class);

        $this->mockAuth->shouldReceive('getIdentity->getUser')->andReturn(m::mock());

        $mockProperty = m::mock()
            ->shouldReceive('getValue')->once()->with($mockEntity)->andReturn('unit_OldVal')
            ->shouldReceive('setValue')->once()->with($mockEntity, null)
            ->getMock();

        $this->mockMeta
            ->shouldReceive('getReflectionProperty')->once()->andReturn($mockProperty)
            ->shouldReceive('hasAssociation')->once()->andReturn(false);

        $this->mockEm
            ->shouldReceive('getClassMetadata')->once()->andReturn($this->mockMeta)
            ->shouldReceive('persist')->never();

        $this->mockUow
            ->shouldReceive('propertyChanged')->never()
            ->shouldReceive('scheduleExtraUpdate')->once();

        $this->mockIdentityProvider
            ->shouldReceive('getMasqueradedAsSystemUser')
            ->andReturn(false)
            ->once()
            ->getMock();

        //  call
        $lifecycleEvent = new LifecycleEventArgs($mockEntity, $this->mockEm);
        $this->sut->preSoftDelete($lifecycleEvent);
    }

    /**
     * @dataProvider dpTestModifiedBy
     */
    public function testModifiedBy($currentUser, $expect)
    {
        $mockEntity = new EntityStub();

        $this->mockAuth->shouldReceive('getIdentity->getUser')->andReturn($currentUser);

        $field = 'lastModifiedBy';
        $oldValue = 'unit_OldVal';

        $mockPropery = m::mock()
            ->shouldReceive('getValue')->once()->with($mockEntity)->andReturn($oldValue)
            ->shouldReceive('setValue')->once()->with($mockEntity, $expect)
            ->getMock();

        $this->mockMeta
            ->shouldReceive('getReflectionProperty')->once()->with($field)->andReturn($mockPropery)
            ->shouldReceive('hasAssociation')->once()->with($field)->andReturn(true);

        $this->mockEm
            ->shouldReceive('getClassMetadata')->once()->with(EntityStub::class)->andReturn($this->mockMeta)
            ->shouldReceive('persist')->times($expect ? 1 : 0)->with($expect);

        $this->mockUow
            ->shouldReceive('propertyChanged')->once()->with($mockEntity, $field, $oldValue, $expect)
            ->shouldReceive('scheduleExtraUpdate')->once()->with(
                $mockEntity,
                [
                    $field => [$oldValue, $expect],
                ]
            );

        $this->mockIdentityProvider
            ->shouldReceive('getMasqueradedAsSystemUser')
            ->andReturn(false)
            ->once()
            ->getMock();

        //  call
        $lifecycleEvent = new LifecycleEventArgs($mockEntity, $this->mockEm);
        $this->sut->preSoftDelete($lifecycleEvent);
    }

    public function dpTestModifiedBy()
    {
        $mockUser = Entity\User\User::create(
            'abc',
            Entity\User\User::USER_TYPE_OPERATOR,
            ['loginId' => 'loginId']
        );

        return [
            [
                'currentUser' => $mockUser,
                'expectUpdate' => $mockUser,
            ],
            [
                'currentUser' => Entity\User\User::anon(),
                'expect' => null,
            ],
            [
                'currentUser' => null,
                'expect' => null,
            ],
        ];
    }

    public function testGetModifiedByUserSystem()
    {
        $mockEntity = new EntityStub();

        $user = new UserEntity('pid', 'system');
        $user->setLoginId('loginId');
        $user->setId(1);

        $this->mockUserRepo
            ->shouldReceive('fetchById')
            ->with(1)
            ->andReturn($user);

        $field = 'lastModifiedBy';
        $oldValue = 'unit_OldVal';

        $mockPropery = m::mock()
            ->shouldReceive('getValue')->once()->with($mockEntity)->andReturn($oldValue)
            ->shouldReceive('setValue')->once()->with($mockEntity, $user)
            ->getMock();

        $this->mockMeta
            ->shouldReceive('getReflectionProperty')->once()->with($field)->andReturn($mockPropery)
            ->shouldReceive('hasAssociation')->once()->with($field)->andReturn(true);

        $this->mockEm
            ->shouldReceive('getClassMetadata')->once()->with(EntityStub::class)->andReturn($this->mockMeta)
            ->shouldReceive('persist')->times($user ? 1 : 0)->with($user);

        $this->mockUow
            ->shouldReceive('propertyChanged')->once()->with($mockEntity, $field, $oldValue, $user)
            ->shouldReceive('scheduleExtraUpdate')->once()->with(
                $mockEntity,
                [
                    $field => [$oldValue, $user],
                ]
            );

        $this->mockIdentityProvider
            ->shouldReceive('getMasqueradedAsSystemUser')
            ->andReturn(true)
            ->once()
            ->getMock();

        //  call
        $lifecycleEvent = new LifecycleEventArgs($mockEntity, $this->mockEm);
        $this->sut->preSoftDelete($lifecycleEvent);
    }
}
