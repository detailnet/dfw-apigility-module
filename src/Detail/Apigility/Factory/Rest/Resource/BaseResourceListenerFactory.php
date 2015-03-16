<?php

namespace Detail\Apigility\Factory\Rest\Resource;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\Exception\ConfigException;

abstract class BaseResourceListenerFactory implements
    FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Apigility\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\Apigility\Options\ModuleOptions');
        $normalizationOptions = $moduleOptions->getNormalization();

        /** @var \Detail\Normalization\Normalizer\NormalizerInterface $normalizer */
        $normalizer = $serviceLocator->get($normalizationOptions->getNormalizer());

        $listenerClass = $this->getListenerClass();

        if (!class_exists($listenerClass)) {
            throw new ConfigException(
                sprintf(
                    'Invalid listener class "%s" specified; must be a valid class name',
                    $listenerClass
                )
            );
        }

        /** @var \Detail\Apigility\Rest\Resource\BaseResourceListener $listener */
        $listener = new $listenerClass($normalizer);

        // Always apply paging related settings from controller (config) to listener
        $controllerConfig = $this->getRestControllerConfig($serviceLocator, $listenerClass);

        if (isset($controllerConfig['page_size'])) {
            $listener->setPageSize($controllerConfig['page_size']);
        }

        if (isset($controllerConfig['page_size_param'])) {
            $listener->setPageSizeParam($controllerConfig['page_size_param']);
        }

        return $listener;
    }

    /**
     * Get ZF-REST's controller config for the given listener.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $listenerClass
     * @return array
     */
    protected function getRestControllerConfig(ServiceLocatorInterface $serviceLocator, $listenerClass)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['zf-rest'])) {
            return array();
        }

        foreach ($config['zf-rest'] as $controllerClass => $controllerConfig) {
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
