<?php

namespace Detail\Apigility\Factory\Hydrator;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\Hydrator\NormalizerBasedHydrator;

class NormalizerBasedHydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Apigility\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\Apigility\Options\ModuleOptions');

        /** @var \Detail\Normalization\Normalizer\NormalizerInterface $normalizer */
        $normalizer = $serviceLocator->get($moduleOptions->getNormalizer());

        return new NormalizerBasedHydrator($normalizer);
    }
}
