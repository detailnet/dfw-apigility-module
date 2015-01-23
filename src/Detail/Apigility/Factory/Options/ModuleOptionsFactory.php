<?php

namespace Detail\Apigility\Factory\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\Apigility\Exception\ConfigException;
use Detail\Apigility\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return ModuleOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['detail_apigility'])) {
            throw new ConfigException('Config for Detail\Apigility is not set');
        }

        return new ModuleOptions($config['detail_apigility']);
    }
}
