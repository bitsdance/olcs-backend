<?php

/**
 * Create User Selfserve
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\User;

use Dvsa\Contracts\Auth\Exceptions\ClientException;
use Dvsa\Olcs\Api\Domain\AuthAwareInterface;
use Dvsa\Olcs\Api\Domain\AuthAwareTrait;
use Dvsa\Olcs\Api\Domain\Command\Email\SendUserCreated as SendUserCreatedDto;
use Dvsa\Olcs\Api\Domain\Command\Email\SendUserTemporaryPassword as SendUserTemporaryPasswordDto;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractUserCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\Exception\BadRequestException;
use Dvsa\Olcs\Api\Domain\OpenAmUserAwareInterface;
use Dvsa\Olcs\Api\Domain\OpenAmUserAwareTrait;
use Dvsa\Olcs\Api\Entity\ContactDetails\ContactDetails;
use Dvsa\Olcs\Api\Entity\User\User;
use Dvsa\Olcs\Api\Service\OpenAm\Client;
use Dvsa\Olcs\Api\Service\OpenAm\FailedRequestException;
use Dvsa\Olcs\Auth\Service\PasswordService;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Transfer\Command\User\CreateUserSelfserve as CreateUserSelfserveCommand;
use Laminas\Authentication\Adapter\ValidatableAdapterInterface;

/**
 * Create User Selfserve
 */
final class CreateUserSelfserve extends AbstractUserCommandHandler implements
    AuthAwareInterface,
    TransactionedInterface,
    OpenAmUserAwareInterface
{
    use AuthAwareTrait,
        OpenAmUserAwareTrait;

    protected $repoServiceName = 'User';

    protected $extraRepos = ['ContactDetails'];
    /**
     * @var \Dvsa\Olcs\Api\Service\OpenAm\UserInterface|ValidatableAdapterInterface|null
     */
    private $adapter;

    /**
     * @var PasswordService
     */
    private $passwordService;

    /**
     *
     * @param ValidatableAdapterInterface|null $adapter
     * @param PasswordService $passwordService
     */
    public function __construct(PasswordService $passwordService, ?ValidatableAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->passwordService = $passwordService;
    }

    /**
     * Handle command
     *
     * @param CommandInterface $command command
     *
     * @return \Dvsa\Olcs\Api\Domain\Command\Result
     */
    public function handleCommand(CommandInterface $command)
    {
        assert($command instanceof CreateUserSelfserveCommand);

        //TODO: Remove once OpenAM is removed.
        if (is_null($this->adapter)) {
            $this->adapter = $this->getOpenAmUser();
        }

        $data = $command->getArrayCopy();

        // validate username
        $this->validateUsername($data['loginId']);

        // copy user type from the current loggedin user
        switch ($this->getCurrentUser()->getUserType()) {
            case User::USER_TYPE_PARTNER:
                $data['userType'] = User::USER_TYPE_PARTNER;
                $data['partnerContactDetails'] = $this->getCurrentUser()->getPartnerContactDetails()->getId();
                break;
            case User::USER_TYPE_LOCAL_AUTHORITY:
                $data['userType'] = User::USER_TYPE_LOCAL_AUTHORITY;
                $data['localAuthority'] = $this->getCurrentUser()->getLocalAuthority()->getId();
                break;
            case User::USER_TYPE_OPERATOR:
            case User::USER_TYPE_TRANSPORT_MANAGER:
                $data['userType'] = User::USER_TYPE_OPERATOR;
                $data['organisations'] = array_map(
                    function ($item) {
                        return $item->getOrganisation();
                    },
                    $this->getCurrentUser()->getOrganisationUsers()->toArray()
                );
                break;
            default:
                // only available to specific user types
                throw new BadRequestException('User type must be provided');
        }

        // populate roles based on the user type and permission
        $data['roles'] = User::getRolesByUserType($data['userType'], $data['permission']);

        $user = User::create(
            $this->generatePid($command->getLoginId()),
            $data['userType'],
            $this->getRepo()->populateRefDataReference($data)
        );

        // create new contact details
        $user->setContactDetails(
            ContactDetails::create(
                $this->getRepo()->getRefdataReference(ContactDetails::CONTACT_TYPE_USER),
                $this->getRepo('ContactDetails')->populateRefDataReference(
                    $command->getContactDetails()
                )
            )
        );

        $this->getRepo()->save($user);

        $password = $this->passwordService->generatePassword();
        try {
            $this->storeUserInAuthService($command, $password);
        } catch (ClientException | FailedRequestException $e) {
            $this->getRepo()->delete($user);
            throw new \Exception("Unable to store user in Auth Service", $e->getCode(), $e);
        }

        try {
            // send welcome email
            $this->handleSideEffect(
                SendUserCreatedDto::create(
                    [
                        'user' => $user->getId(),
                    ]
                )
            );

            // send temporary password email
            $this->handleSideEffect(
                SendUserTemporaryPasswordDto::create(
                    [
                        'user' => $user->getId(),
                        'password' => $password,
                    ]
                )
            );
        } catch (\Exception $e) {
            // swallow any exception
        }

        $result = new Result();
        $result->addId('user', $user->getId());
        $result->addMessage('User created successfully');

        return $result;
    }

    /**
     * @param string $loginId
     * @return string
     * @TODO: Remove once OpenAM removed
     */
    private function generatePid(string $loginId)
    {
        if ($this->adapter instanceof ValidatableAdapterInterface) {
            return null;
        }
        return $this->adapter->generatePid($loginId);
    }

    /**
     * @throws FailedRequestException
     * @throws ClientException
     * @todo: Call directly from handle() once OpenAM removed
     */
    private function storeUserInAuthService(CreateUserSelfserveCommand $command, string &$password)
    {
        if ($this->adapter instanceof ValidatableAdapterInterface) {
            $this->adapter->register(
                $command->getLoginId(),
                $password,
                $command->getContactDetails()['emailAddress']
            );
        } else {
            $this->adapter->registerUser(
                $command->getLoginId(),
                $command->getContactDetails()['emailAddress'],
                Client::REALM_SELFSERVE,
                function ($params) use (&$password) {
                    $password = $params['password'];
                }
            );
        }
    }
}
