<?php

namespace Dvsa\Olcs\Api\Rbac;

use Dvsa\Olcs\Api\Domain\Repository\RepositoryInterface;
use Dvsa\Olcs\Api\Entity\User\User;
use Zend\Http\Header\GenericHeader;
use ZfcRbac\Identity\IdentityProviderInterface;
use ZfcRbac\Identity\IdentityInterface;
use Zend\Http\Request;

/**
 * Identity Provider
 */
class PidIdentityProvider implements IdentityProviderInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $headerName;

    /**
     * @var Identity;
     */
    private $identity;

    public function __construct(RepositoryInterface $repository, Request $request, $headerName)
    {
        $this->repository = $repository;
        $this->request = $request;
        $this->headerName = $headerName;
    }

    private function authenticate()
    {
        $pid = $this->request->getHeader($this->headerName, new GenericHeader())->getFieldValue();

        if (!empty($pid)) {
            return $this->repository->fetchByPid($pid);
        }

        return null;
    }

    /**
     * Get the identity
     *
     * @return IdentityInterface|null
     */
    public function getIdentity()
    {
        if ($this->identity === null) {
            $user = $this->authenticate();
            if ($user === null) {
                $this->identity = new Identity(User::anon());
            } else {
                $this->identity = new Identity($user);
            }
        }

        return $this->identity;
    }
}
