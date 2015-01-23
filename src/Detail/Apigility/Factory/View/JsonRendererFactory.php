<?php

namespace Detail\Apigility\Factory\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\View\JsonRenderer;

class JsonRendererFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $helpers = $serviceLocator->get('ViewHelperManager');

        /** @var \Detail\Apigility\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\Apigility\Options\ModuleOptions');

        /** @var \Detail\Normalization\Normalizer\NormalizerInterface $normalizer */
        $normalizer = $serviceLocator->get($moduleOptions->getNormalizer());

        $renderer = new JsonRenderer($normalizer);
//        $renderer->setHelperPluginManager($helpers);

        return $renderer;
    }
}
