<?php

namespace Detail\Apigility\Factory\Hydrator;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\Hydrator\NormalizerBasedHydrationListener;

class NormalizerBasedHydrationListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Apigility\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\Apigility\Options\ModuleOptions');
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var \Detail\Apigility\Hydrator\NormalizerBasedHydrator $normalizationBasedHydrator */
        $normalizationBasedHydrator = $serviceLocator->get('Detail\Apigility\Hydrator\NormalizerBasedHydrator');

        /** @var \Detail\Apigility\Normalization\NormalizationGroupsProviderInterface $normalizationGroupsProvider */
        $normalizationGroupsProvider = $serviceLocator->get($normalizationOptions->getGroupsProvider());

        return new NormalizerBasedHydrationListener($normalizationBasedHydrator, $normalizationGroupsProvider);
    }
}
