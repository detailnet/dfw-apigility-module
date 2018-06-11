<?php

namespace Detail\Apigility\Factory\Hydrator;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Apigility\Hydrator\NormalizerBasedHydrator;
use Detail\Apigility\Options\ModuleOptions;

class NormalizerBasedHydratorFactory implements
    FactoryInterface
{
    /**
     * Create NormalizerBasedHydrator
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NormalizerBasedHydrator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::CLASS);
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var \Detail\Normalization\Normalizer\NormalizerInterface $normalizer */
        $normalizer = $container->get($normalizationOptions->getNormalizer());

        return new NormalizerBasedHydrator($normalizer);
    }
}
