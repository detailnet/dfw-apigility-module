<?php

namespace Detail\Apigility\Factory\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\View\XmlRenderer;

class XmlRendererFactory implements
    FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $helpers = $serviceLocator->get('ViewHelperManager');

        /** @var \Detail\Apigility\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\Apigility\Options\ModuleOptions');
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var \Detail\Normalization\Normalizer\NormalizerInterface $normalizer */
        $normalizer = $serviceLocator->get($normalizationOptions->getNormalizer());

        /** @var \Detail\Apigility\Normalization\NormalizationGroupsProviderInterface $normalizationGroupsProvider */
        $normalizationGroupsProvider = $serviceLocator->get($normalizationOptions->getGroupsProvider());

        $renderer = new XmlRenderer($normalizer, $normalizationGroupsProvider);
//        $renderer->setHelperPluginManager($helpers);

        return $renderer;
    }
}
