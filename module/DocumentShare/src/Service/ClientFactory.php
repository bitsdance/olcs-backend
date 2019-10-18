<?php

namespace Dvsa\Olcs\DocumentShare\Service;

use Dvsa\Olcs\Api\Entity\User\User;
use Dvsa\Olcs\Utils\Client\ClientAdapterLoggingWrapper;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Client as HttpClient;
use Sabre\DAV\Client as SabreClient;
use ZfcRbac\Service\AuthorizationService;

/**
 * Class ClientFactory
 */
class ClientFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator Service manager
     *
     * @return HttpClient
     * @throws \RuntimeException
     */
    public function getHttpClient(ServiceLocatorInterface $serviceLocator): HttpClient
    {
        $options = $this->getOptions($serviceLocator, 'http');
        $httpClient = new HttpClient();
        $httpClient->setOptions($options);

        $wrapper = new ClientAdapterLoggingWrapper();
        $wrapper->wrapAdapter($httpClient);
        $wrapper->setShouldLogData(false);

        return $httpClient;
    }

    /**
     * Gets options from configuration based on name.
     *
     * @param ServiceLocatorInterface $sl  Service Manager
     * @param string                  $key Key
     *
     * @return array
     * @throws \RuntimeException
     */
    public function getOptions(ServiceLocatorInterface $sl, $key)
    {
        if (is_null($this->options)) {
            $options = $sl->get('Configuration');
            $this->options = isset($options['document_share']) ? $options['document_share'] : array();
        }

        $options = isset($this->options[$key]) ? $this->options[$key] : null;

        if (null === $options) {
            throw new RuntimeException(
                sprintf(
                    'Options could not be found in "document_share.%s".',
                    $key
                )
            );
        }

        return $options;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @param                         $requestedName
     *
     * @return array
     */
    private function getConfiguration(ServiceLocatorInterface $serviceLocator): array
    {
        $clientOptions = $this->getOptions($serviceLocator, 'client');


        if (!isset($clientOptions['baseuri']) || empty($clientOptions['baseuri'])) {
            throw new RuntimeException('Missing required option document_share.client.baseuri');
        }

        if (!isset($clientOptions['workspace']) || empty($clientOptions['workspace'])) {
            throw new RuntimeException('Missing required option document_share.client.workspace');
        }

        $clientOptions['httpClient'] = $this->getHttpClient($serviceLocator);
        return $clientOptions;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): DocumentStoreInterface
    {
        $clientOptions = $this->getConfiguration($serviceLocator);

        if ($this->getClientType($serviceLocator) === WebDavClient::class) {
            $clientOptions = $this->getWebDavConfig($clientOptions);
            $sabreClient = new SabreClient(
                [
                    'baseUri' => $clientOptions['webdav_baseuri'],
                    'username' => $clientOptions['username'],
                    'password' => $clientOptions['password']
                ]
            );

            $adapter = new WebDAVAdapter($sabreClient, $clientOptions['workspace']);
            $fileSystem = new Filesystem($adapter);
            return new WebDavClient($fileSystem);
        } else {
            $client = new DocManClient(
                $this->getHttpClient($serviceLocator),
                $clientOptions['baseuri'],
                $clientOptions['workspace']
            );
            if (isset($clientOptions['uuid'])) {
                $client->setUuid($clientOptions['uuid']);
            }

            return $client;
        }
    }


    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return string
     */
    private function getClientType(ServiceLocatorInterface $serviceLocator): string
    {
        $authService = $serviceLocator->get(AuthorizationService::class);

        /** @var User $currentUser */
        $currentUser = $authService->getIdentity()->getUser();

        return ($currentUser->getOsType() === User::USER_OS_TYPE_WINDOWS_10) ? WebDavClient::class : DocManClient::class;
    }

    /**
     * @param $requestedName
     * @param $clientOptions
     *
     * @return mixed
     */
    private function getWebDavConfig($clientOptions)
    {
        if (!isset($clientOptions['username']) || empty($clientOptions['username'])) {
            throw new RuntimeException('Missing required option document_share.client.username for webdav client');
        }

        if (!isset($clientOptions['password']) || empty($clientOptions['password'])) {
            throw new RuntimeException('Missing required option document_share.client.password for webdav client');
        }
        return $clientOptions;
    }
}
