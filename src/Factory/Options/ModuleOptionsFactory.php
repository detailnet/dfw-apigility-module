<?php

namespace Detail\Apigility\Factory\Options;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Apigility\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * Create ModuleOptions
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ModuleOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!isset($config['detail_apigility'])) {
            throw new ServiceNotCreatedException('Config for Detail\Apigility is not set');
        }

        return new ModuleOptions($config['detail_apigility']);
    }
}
