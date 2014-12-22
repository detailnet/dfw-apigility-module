<?php

namespace Application\Core\Hydrator;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class NormalizerBasedHydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @todo Make normalizer service name configurable */
        /** @var \Application\Core\Normalizer\JMSSerializerBasedNormalizer $normalizer */
        $normalizer = $serviceLocator->get('Application\Core\Normalizer\JMSSerializerBasedNormalizer');

        return new NormalizerBasedHydrator($normalizer);
    }
}
