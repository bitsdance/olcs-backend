<?php

namespace Dvsa\Olcs\Api\Service\Nr\InputFilter;

use Dvsa\Olcs\Api\Service\InputFilter\Input;
use Dvsa\Olcs\Api\Service\Nr\Filter\LicenceNumber;
use Dvsa\Olcs\Api\Service\Nr\Filter\Format\MemberStateCode;
use Dvsa\Olcs\Api\Service\Nr\Filter\Vrm as VrmFilter;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

/**
 * Class ComplianceEpisodeInputFactory
 * @package Dvsa\Olcs\Api\Service\Nr\InputFilter
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class ComplianceEpisodeInputFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator Service locator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): Input
    {
        return $this->__invoke($serviceLocator, Input::class);
    }

    /**
     * invoke method
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Input
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Input
    {
        $fm = $container->get('FilterManager');
        $service = new Input('compliance_episode');
        $filterChain = $service->getFilterChain();
        $filterChain
            ->attach($fm->get(VrmFilter::class))
            ->attach($fm->get(LicenceNumber::class))
            ->attach($fm->get(MemberStateCode::class));
        return $service;
    }
}
