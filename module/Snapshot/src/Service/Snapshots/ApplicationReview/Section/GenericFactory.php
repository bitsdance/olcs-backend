<?php

namespace Dvsa\Olcs\Snapshot\Service\Snapshots\ApplicationReview\Section;

use Dvsa\Olcs\Api\Service\Traits\GenericFactoryCreateServiceTrait;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;

class GenericFactory implements FactoryInterface
{
    use GenericFactoryCreateServiceTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(AbstractReviewServiceServices::class),
        );
    }
}
