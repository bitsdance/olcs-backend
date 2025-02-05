<?php

namespace Dvsa\OlcsTest\Api\Domain\Validation\Validators;

use Dvsa\Olcs\Api\Domain\Validation\Validators\CanAccessLicenceForSurrender;
use Dvsa\Olcs\Api\Entity\Licence\Licence;
use Dvsa\Olcs\Api\Entity\Surrender;
use Dvsa\Olcs\Api\Entity\User\Permission;
use \Mockery as m;

class CanAccessLicenceForSurrenderTest extends AbstractValidatorsTestCase
{
    public function setUp(): void
    {
        $this->sut = new CanAccessLicenceForSurrender();
        parent::setUp();
    }

    /**
     * @dataProvider dpLicencePermissions
     */
    public function testIsValidExternalUserLicenceOwner(
        $permission,
        $isOwner,
        $licenceState,
        $surrenderStatus,
        $expected
    ) {
        $this->setIsGranted($permission, true);
        $this->auth->shouldReceive('getIdentity')->andReturn(null);
        $entity = m::mock(Licence::class);
        $entity->shouldReceive('getId')->once()->andReturn(111);
        switch ($this->dataName()) {
            case 'selfservice-user-owner':
                $this->setIsValid('isOwner', [$entity], $isOwner);
                $entity->shouldReceive('getStatus->getId')->once()->andReturn($licenceState);

                break;
            case 'selfservice-user-owner-not-surrendered':
                $this->setIsGranted(Permission::INTERNAL_USER, false);
                $this->setIsValid('isOwner', [$entity], $isOwner);
                $entity->shouldReceive('getStatus->getId')->once()->andReturn($licenceState);

                break;

            case 'internal-user-not-surrendered':
                $this->setIsGranted(Permission::SELFSERVE_USER, false);
                $this->setIsValid('isOwner', [$entity], $isOwner);
                break;
            case 'internal-user-surrendered':
                $this->setIsGranted(Permission::SELFSERVE_USER, false);
                $this->setIsValid('isOwner', [$entity], $isOwner);
                break;
            case 'selfservice-user-surrender-submitted':
                $this->setIsValid('isOwner', [$entity], $isOwner);
                $entity->shouldReceive('getStatus->getId')->once()->andReturn($licenceState);
        }


        $repo = $this->mockRepo('Licence');
        $repo->shouldReceive('fetchById')->with(111)->andReturn($entity);
        $surrenderRepo = $this->mockRepo('Surrender');
        $surrenderEntity = m::mock(Surrender::class);
        $surrenderEntity->shouldReceive('getId')->andReturn(1);
        $surrenderEntity->shouldReceive('getStatus->getId')->andReturn($surrenderStatus);
        $surrenderRepo->shouldReceive('fetchOneByLicenceId')->andReturn(
            $surrenderEntity
        );
        $this->setIsValid('isOwner', [$surrenderEntity], $isOwner);
        $this->assertEquals($expected, $this->sut->isValid($entity));
    }

    public function dpLicencePermissions()
    {
        return [
            'selfservice-user-owner' => [
                Permission::SELFSERVE_USER,
                true,
                Licence::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION,
                Surrender::SURRENDER_STATUS_APPROVED,
                false
            ],
            'selfservice-user-owner-not-surrendered' => [
                Permission::SELFSERVE_USER,
                true,
                Licence::LICENCE_STATUS_VALID,
                Surrender::SURRENDER_STATUS_DISCS_COMPLETE,
                true

            ],
            'internal-user-not-surrendered' => [
                Permission::INTERNAL_USER,
                false,
                Licence::LICENCE_STATUS_VALID,
                Surrender::SURRENDER_STATUS_COMM_LIC_DOCS_COMPLETE,
                true
            ],
            'internal-user-surrendered' => [
                Permission::INTERNAL_USER,
                false,
                Licence::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION,
                Surrender::SURRENDER_STATUS_SIGNED,
                true
            ],
            'selfservice-user-surrender-submitted' => [
                Permission::SELFSERVE_USER,
                true,
                Licence::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION,
                Surrender::SURRENDER_STATUS_SUBMITTED,
                false
            ]
        ];
    }
}
