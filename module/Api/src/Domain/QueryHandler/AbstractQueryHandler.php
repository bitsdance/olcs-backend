<?php

namespace Dvsa\Olcs\Api\Domain\QueryHandler;

use Dvsa\Olcs\Api\Domain\CacheAwareInterface;
use Dvsa\Olcs\Api\Domain\HandlerEnabledTrait;
use Dvsa\Olcs\Api\Domain\Logger\EntityAccessLogger;
use Dvsa\Olcs\Api\Domain\UploaderAwareInterface;
use Dvsa\Olcs\Api\Service\Translator\TranslationLoader;
use Dvsa\Olcs\Transfer\Service\CacheEncryption as CacheEncryptionService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Dvsa\Olcs\Api\Domain\Repository\RepositoryInterface;
use Dvsa\Olcs\Api\Domain\Exception\RuntimeException;
use ZfcRbac\Service\AuthorizationService;
use Dvsa\Olcs\Api\Domain\AuthAwareInterface;
use Dvsa\Olcs\Api\Domain\NationalRegisterAwareInterface;
use Dvsa\Olcs\Api\Domain\OpenAmUserAwareInterface;
use Dvsa\Olcs\Api\Domain\ToggleAwareInterface;
use Dvsa\Olcs\Api\Domain\ToggleRequiredInterface;
use Dvsa\Olcs\Api\Domain\TranslationLoaderAwareInterface;
use Dvsa\Olcs\Api\Domain\TranslatorAwareInterface;
use Dvsa\Olcs\Api\Service\OpenAm\UserInterface;
use Dvsa\Olcs\Api\Service\Toggle\ToggleService;
use Dvsa\Olcs\Api\Domain\AuthAwareTrait;
use Dvsa\Olcs\Transfer\Query\Cache\ById as CacheById;
use Olcs\Logging\Log\Logger;
use Laminas\ServiceManager\Exception\ExceptionInterface as LaminasServiceException;
use Interop\Container\ContainerInterface;

abstract class AbstractQueryHandler implements QueryHandlerInterface, FactoryInterface, AuthAwareInterface
{
    use AuthAwareTrait;
    use HandlerEnabledTrait;

    /**
     * The name of the default repo
     */
    protected $repoServiceName;

    /**
     * Tell the factory which repositories to lazy load
     */
    protected $extraRepos = [];

    /**
     * Store the instantiated repos
     *
     * @var RepositoryInterface[]
     */
    private $repos = [];

    /**
     * @var \Dvsa\Olcs\Api\Domain\QueryHandlerManager
     */
    private $queryHandler;

    private $repoManager;

    /**
     * @var \Dvsa\Olcs\Api\Domain\CommandHandlerManager
     */
    private $commandHandler;

    /**
     * @var EntityAccessLogger|null
     */
    private $auditLogger;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator Service locator
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestedName = null)
    {
        return $this->__invoke($serviceLocator, $requestedName);
    }

    /**
     * Get the repository
     *
     * @param string $name Repository name
     *
     * @return RepositoryInterface
     *
     * @throws RuntimeException
     */
    protected function getRepo($name = null)
    {
        if ($name === null) {
            $name = $this->repoServiceName;
        }

        if (!in_array($name, $this->extraRepos)) {
            throw new RuntimeException('You have not injected the ' . $name . ' repository');
        }

        // Lazy load repository
        if (!isset($this->repos[$name])) {
            $this->repos[$name] = $this->repoManager->get($name);
        }

        return $this->repos[$name];
    }

    /**
     * @param string $cacheIdentifier
     * @param string $uniqueId
     *
     * @return Result
     */
    protected function getCacheById(string $cacheIdentifier, string $uniqueId = ''): Result
    {
        $params = [
            'id' => $cacheIdentifier,
            'uniqueId' => $uniqueId,
        ];

        $qry = CacheById::create($params);
        return $this->queryHandler->handleQuery($qry);
    }

    /**
     * Get query handler
     *
     * @return \Dvsa\Olcs\Api\Domain\QueryHandlerManager
     */
    protected function getQueryHandler()
    {
        return $this->queryHandler;
    }

    /**
     * Get result list
     *
     * @param mixed $objects objects
     * @param array $bundle  Result bundle to retrieve
     *
     * @return array
     */
    protected function resultList($objects, array $bundle = [])
    {
        return (new ResultList($objects, $bundle))->serialize();
    }

    /**
     * Create result object
     *
     * @param mixed $object Result object
     * @param array $bundle bundle array
     * @param array $values values
     *
     * @return Result
     */
    protected function result($object, array $bundle = [], $values = [])
    {
        return new Result($object, $bundle, $values);
    }

    /**
     * @return EntityAccessLogger
     */
    protected function getAuditLogger(): EntityAccessLogger
    {
        if (null === $this->auditLogger) {
            $this->auditLogger = $this->queryHandler->getServiceLocator()->get(EntityAccessLogger::class);
        }
        return $this->auditLogger;
    }

    /**
     * Create a read audit for an entity
     *
     * @param object $entity The entity which has been read
     * @deprecated Use getAuditLogger which injects EntityAccessLogger into your class as a dependency.
     *             Call the "logAccessToEntity" method on that directly.
     */
    protected function auditRead($entity)
    {
        $this->getAuditLogger()->logAccessToEntity($entity);
    }

    /**
     * Get command handler
     *
     * @return \Dvsa\Olcs\Api\Domain\CommandHandlerManager
     */
    protected function getCommandHandler()
    {
        return $this->commandHandler;
    }

    /**
     * Warnings suppressed as by design this is just a series of 'if' conditions
     *
     * @param ServiceLocatorInterface $mainServiceLocator service locator
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function applyInterfaces($mainServiceLocator)
    {
        if ($this instanceof ToggleRequiredInterface || $this instanceof ToggleAwareInterface) {
            $toggleService = $mainServiceLocator->get(ToggleService::class);
            $this->setToggleService($toggleService);
        }

        if ($this instanceof AuthAwareInterface) {
            $this->setAuthService($mainServiceLocator->get(AuthorizationService::class));
        }

        if ($this instanceof UploaderAwareInterface) {
            $this->setUploader($mainServiceLocator->get('FileUploader'));
        }

        if ($this instanceof \Dvsa\Olcs\Api\Domain\CpmsAwareInterface) {
            $this->setCpmsService($mainServiceLocator->get('CpmsHelperService'));
        }

        if ($this instanceof \Dvsa\Olcs\Api\Domain\CompaniesHouseAwareInterface) {
            $this->setCompaniesHouseService($mainServiceLocator->get('CompaniesHouseService'));
        }

        if ($this instanceof \Dvsa\Olcs\Address\Service\AddressServiceAwareInterface) {
            $this->setAddressService($mainServiceLocator->get('AddressService'));
        }

        if ($this instanceof NationalRegisterAwareInterface) {
            $this->setNationalRegisterConfig($mainServiceLocator->get('Config')['nr']);
        }

        if ($this instanceof OpenAmUserAwareInterface) {
            $this->setOpenAmUser($mainServiceLocator->get(UserInterface::class));
        }

        if ($this instanceof CacheAwareInterface) {
            /** @var CacheEncryptionService $cacheEncryptionService */
            $cacheEncryptionService = $mainServiceLocator->get(CacheEncryptionService::class);
            $this->setCache($cacheEncryptionService);
        }

        if ($this instanceof TranslationLoaderAwareInterface) {
            $translationLoader = $mainServiceLocator->get('TranslatorPluginManager')->get(TranslationLoader::class);
            $this->setTranslationLoader($translationLoader);
        }

        if ($this instanceof TranslatorAwareInterface) {
            $translator = $mainServiceLocator->get('translator');
            $this->setTranslator($translator);
        }
    }

    /**
     * Laminas ServiceManager masks exceptions behind a simple 'service not created'
     * message so here we inspect the 'previous exception' chain and log out
     * what the actual errors were, before rethrowing the original execption.
     *
     * @param \Exception $e exception
     *
     * @return void
     * @throws \Exception rethrows original Exception
     */
    private function logServiceExceptions(\Exception $e)
    {
        $rethrow = $e;

        do {
            Logger::warn(get_class($this) . ': ' . $e->getMessage());
            $e = $e->getPrevious();
        } while ($e);

        throw $rethrow;
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return $this
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mainServiceLocator = $container->getServiceLocator();
        try {
            $this->applyInterfaces($mainServiceLocator);
        } catch (LaminasServiceException $e) {
            $this->logServiceExceptions($e);
        }
        $this->repoManager = $mainServiceLocator->get('RepositoryServiceManager');
        $this->extraRepos[] = $this->repoServiceName;
        $this->queryHandler = $container;
        $this->commandHandler = $mainServiceLocator->get('CommandHandlerManager');
        return $this;
    }
}
