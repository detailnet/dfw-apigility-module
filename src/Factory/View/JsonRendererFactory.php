<?php

namespace Detail\Apigility\Factory\View;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Normalization\Normalizer\NormalizerInterface;

use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;
use Detail\Apigility\Options\ModuleOptions;
use Detail\Apigility\View\JsonRenderer;

class JsonRendererFactory implements
    FactoryInterface
{
    /**
     * Create JsonRenderer
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return JsonRenderer
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
//        $helpers = $serviceLocator->get('ViewHelperManager');

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::CLASS);
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var NormalizerInterface $normalizer */
        $normalizer = $container->get($normalizationOptions->getNormalizer());

        /** @var NormalizationGroupsProviderInterface $normalizationGroupsProvider */
        $normalizationGroupsProvider = $container->get($normalizationOptions->getGroupsProvider());

        $renderer = new JsonRenderer($normalizer, $normalizationGroupsProvider);
//        $renderer->setHelperPluginManager($helpers);

        return $renderer;
    }
}
