<?php

namespace Dvsa\Olcs\AwsSdk\Factories;

use Aws\S3\S3Client;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

/**
 * Class S3ClientFactory
 *
 * @package Dvsa\Olcs\AwsSdk\Factories
 */
class S3ClientFactory implements FactoryInterface
{


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): S3Client
    {
        return $this->__invoke($serviceLocator, S3Client::class);
    }

    /**
     * invoke method
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return S3Client
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): S3Client
    {
        $config = $container->get('Config');
        $provider = $container->get('AwsCredentialsProvider');
        $s3Client = new S3Client([
            'region' => $config['awsOptions']['region'],
            'version' => $config['awsOptions']['version'],
            'credentials' => $provider
        ]);
        /**
         * @var S3Client
         */
        return $s3Client;
    }
}
