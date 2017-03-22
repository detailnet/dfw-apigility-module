<?php

namespace Detail\Apigility\Factory\Hydrator;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Apigility\Hydrator\NormalizerBasedHydrationListener;
use Detail\Apigility\Hydrator\NormalizerBasedHydrator;
use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;
use Detail\Apigility\Options\ModuleOptions;

class NormalizerBasedHydrationListenerFactory implements
    FactoryInterface
{
    /**
     * Create NormalizerBasedHydrationListener
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return NormalizerBasedHydrationListener
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::CLASS);
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var NormalizerBasedHydrator $normalizationBasedHydrator */
        $normalizationBasedHydrator = $container->get(NormalizerBasedHydrator::CLASS);

        /** @var NormalizationGroupsProviderInterface $normalizationGroupsProvider */
        $normalizationGroupsProvider = $container->get($normalizationOptions->getGroupsProvider());

        return new NormalizerBasedHydrationListener($normalizationBasedHydrator, $normalizationGroupsProvider);
    }
}
