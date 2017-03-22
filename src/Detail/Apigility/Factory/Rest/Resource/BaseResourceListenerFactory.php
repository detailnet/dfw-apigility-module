<?php

namespace Detail\Apigility\Factory\Rest\Resource;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Normalization\Normalizer\NormalizerInterface;

use Detail\Apigility\Exception\ConfigException;
use Detail\Apigility\Options\ModuleOptions;
use Detail\Apigility\Rest\Resource\BaseResourceListener;

abstract class BaseResourceListenerFactory implements
    FactoryInterface
{
    /**
     * Create resource listener
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return BaseResourceListener
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::CLASS);
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var NormalizerInterface $normalizer */
        $normalizer = $container->get($normalizationOptions->getNormalizer());

        $listenerClass = $this->getListenerClass();

        if (!class_exists($listenerClass)) {
            throw new ConfigException(
                sprintf(
                    'Invalid listener class "%s" specified; must be a valid class name',
                    $listenerClass
                )
            );
        }

        /** @var BaseResourceListener $listener */
        $listener = new $listenerClass($normalizer);

        // Always apply paging related settings from controller (config) to listener
        $controllerConfig = $this->getRestControllerConfig($container, $listenerClass);

        if (isset($controllerConfig['page_size'])) {
            $listener->setPageSize($controllerConfig['page_size']);
        }

        if (isset($controllerConfig['page_size_param'])) {
            $listener->setPageSizeParam($controllerConfig['page_size_param']);
        }

        return $listener;
    }

    /**
     * Get ZF-REST's controller config for the given listener
     *
     * @param ContainerInterface $container
     * @param string $listenerClass
     * @return array
     */
    protected function getRestControllerConfig(ContainerInterface $container, $listenerClass)
    {
        $config = $container->get('Config');

        if (!isset($config['zf-rest'])) {
            return array();
        }

        foreach ($config['zf-rest'] as $controllerConfig) {
            if (isset($controllerConfig['listener'])
                && $controllerConfig['listener'] === $listenerClass
            ) {
                return $controllerConfig;
            }
        }

        return array();
    }

    /**
     * @return string
     */
    abstract protected function getListenerClass();
}
