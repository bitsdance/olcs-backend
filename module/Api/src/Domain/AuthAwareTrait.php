<?php

namespace Dvsa\Olcs\Api\Domain;

use Dvsa\Olcs\Api\Domain\QueryHandler\Result;
use Dvsa\Olcs\Api\Entity\Bus\LocalAuthority;
use Dvsa\Olcs\Api\Entity\Licence\Licence;
use Dvsa\Olcs\Api\Rbac\IdentityProviderInterface;
use Dvsa\Olcs\Transfer\FieldType\Traits\TrafficAreas;
use Dvsa\Olcs\Transfer\FieldType\Traits\TrafficAreasOptional;
use Dvsa\Olcs\Transfer\Query\MyAccount\MyAccount;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use ZfcRbac\Service\AuthorizationService;
use Dvsa\Olcs\Api\Domain\Repository\User as UserRepoService;
use Dvsa\Olcs\Api\Entity\User\Permission;

/**
 * Auth Aware
 */
trait AuthAwareTrait
{
    /**
     * @var AuthorizationService
     */
    protected $authService;

    /**
     * @var UserRepoService
     */
    protected $repository;

    /**
     * @param AuthorizationService $service
     *
     * @return void
     */
    public function setAuthService(AuthorizationService $service)
    {
        $this->authService = $service;
    }

    /**
     * @return AuthorizationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param UserRepoService $service
     *
     * @return void
     */
    public function setUserRepository(UserRepoService $service)
    {
        $this->repository = $service;
    }

    /**
     * @return UserRepoService
     */
    public function getUserRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Dvsa\Olcs\Api\Entity\User\User
     */
    public function getCurrentUser()
    {
        $identity = $this->authService->getIdentity();

        if ($identity) {
            return $identity->getUser();
        }
    }

    /**
     * @note Even though this appears to be a one to one relationship, there is only ever one organisation for a user
     *
     * @return \Dvsa\Olcs\Api\Entity\Organisation\Organisation
     */
    public function getCurrentOrganisation()
    {
        $identity = $this->authService->getIdentity();

        $hasOrganisation = !$identity->getUser()->getOrganisationUsers()->isEmpty();

        if ($identity && $hasOrganisation) {
            return $identity->getUser()->getRelatedOrganisation();
        }
    }

    /**
     * @note Even though this appears to be a one to one relationship, there's only ever one local authority for a user
     * @todo olcs-14494 emergency fix, need to clean this up
     *
     * @return LocalAuthority
     */
    public function getCurrentLocalAuthority()
    {
        $identity = $this->authService->getIdentity();

        $localAuthority = $identity->getUser()->getLocalAuthority();

        if ($localAuthority instanceof LocalAuthority) {
            return $localAuthority;
        }
    }

    /**
     * @param mixed $permission permission
     * @param null  $context    context
     *
     * @return bool
     */
    public function isGranted($permission, $context = null)
    {
        return $this->authService->isGranted($permission, $context);
    }

    /**
     * get user
     *
     * @return \Dvsa\Olcs\Api\Entity\User\User
     */
    public function getUser()
    {
        return $this->authService->getIdentity()->getUser();
    }

    /**
     * Note this is only intended for internal users, selfserve users don't have these access permissions
     *
     * Takes an array of traffic areas that will have come from a transfer object.
     * If empty or "all" is selected then return all traffic areas the user has access to
     *
     * @see TrafficAreas
     * @see TrafficAreasOptional
     */
    public function modifyTrafficAreaQueryBasedOnUser(QueryInterface $query): QueryInterface
    {
        $trafficAreas = $query->getTrafficAreas();

        if (empty($trafficAreas) || in_array('all', $trafficAreas)) {
            /**
             * reports have an "other" field which we will need to preserve
             * this will be ignored by anything which doesn't support it via an "in" query
             */
            $additional = ['other'];

            $newData = [
                'trafficAreas' => array_merge($this->getInternalUserTrafficAreas(), $additional),
            ];

            $query->exchangeArray($newData);
        }

        return $query;
    }

    /**
     * get user traffic areas (this data exists for internal users only)
     */
    public function getInternalUserTrafficAreas(): array
    {
        return $this->getUserData()['dataAccess']['trafficAreas'];
    }

    /**
     * gets a copy of the user account data - majority of the time this will come straight from the myaccount cache
     * if the cache doesn't exist we'll have a query handler result instead that will need to be serialized
     *
     * @return array
     */
    public function getUserData(): array
    {
        $accountInfo = $this->getQueryHandler()->handleQuery(MyAccount::create([]));

        if ($accountInfo instanceof Result) {
            return $accountInfo->serialize();
        }

        return $accountInfo;
    }

    /**
     * Does the current user have the Internal user role
     *
     * @return bool
     */
    public function isInternalUser()
    {
        return ($this->isGranted(\Dvsa\Olcs\Api\Entity\User\Permission::INTERNAL_USER));
    }

    /**
     * is user a read only internal user
     *
     * @return bool
     */
    public function isReadOnlyInternalUser()
    {
        $isReadOnlyUser = false;
        $currentUser = $this->getCurrentUser();
        $roles = $currentUser->getRoles()->getValues();

        foreach ($roles as $role) {
            if ($role->getRole() === \Dvsa\Olcs\Api\Entity\User\Role::ROLE_INTERNAL_LIMITED_READ_ONLY) {
                $isReadOnlyUser = true;
            }
        }

        return $isReadOnlyUser;
    }

    /**
     * Is the current user a system user
     *
     * @return bool
     */
    public function isSystemUser()
    {
        return ($this->getCurrentUser() && $this->getCurrentUser()->isSystemUser());
    }

    /**
     * Does the current user have the External user role
     *
     * @return bool
     */
    public function isExternalUser()
    {
        return ($this->isGranted(\Dvsa\Olcs\Api\Entity\User\Permission::SELFSERVE_USER));
    }

    /**
     * Is the current user an anonymous user
     *
     * @return bool
     */
    public function isAnonymousUser()
    {
        return ($this->getCurrentUser()->isAnonymous());
    }

    /**
     * Does the current user have a Local Authority user role
     *
     * @return bool
     */
    public function isLocalAuthority()
    {
        return (
            $this->isGranted(\Dvsa\Olcs\Api\Entity\User\Permission::LOCAL_AUTHORITY_USER) ||
            $this->isGranted(\Dvsa\Olcs\Api\Entity\User\Permission::LOCAL_AUTHORITY_ADMIN)
        );
    }

    /**
     * Does the current user have an Operator user role
     *
     * @return bool
     */
    public function isOperator()
    {
        return (
            $this->isGranted(\Dvsa\Olcs\Api\Entity\User\Permission::OPERATOR_ADMIN) ||
            $this->isGranted(\Dvsa\Olcs\Api\Entity\User\Permission::OPERATOR_USER)
        );
    }

    /**
     * Get system user
     *
     * @return \Dvsa\Olcs\Api\Entity\User\User
     * @throws Exception\NotFoundException
     */
    public function getSystemUser()
    {
        return $this->getUserRepository()->fetchById(IdentityProviderInterface::SYSTEM_USER);
    }

    /**
     * isTransportManager
     *
     * @return bool
     */
    public function isTransportManager(): bool
    {
        return $this->isGranted(Permission::TRANSPORT_MANAGER);
    }

    /**
     * Does the licence belong to the current organisation
     *
     * @param Licence $licence licence
     *
     * @return bool
     */
    public function licenceBelongsToOrganisation(Licence $licence): bool
    {
        return $licence->getRelatedOrganisation()->getId() === $this->getCurrentOrganisation()->getId();
    }
}
