<?php

namespace Application\Core\Session\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class SessionSaveHandlerAbstractFactory implements AbstractFactoryInterface
{
    const CONFIG_KEY = 'session_save_handler';

    /**
     * @var array
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);
        return isset($config[$requestedName]);
    }

    /**
     * {@inheritdoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator)[$requestedName];

        if (!isset($config['class']) || !is_string($config['class'])) {
            throw new ServiceNotCreatedException(
                'Configuration is missing a "class" key, or the value of that key is not a string'
            );
        }

        if (!class_exists($config['class'])) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Invalid save handler class "%s" specified in "class"; must be a valid class name',
                    $config['class']
                )
            );
        }

        $class = $config['class'];

        $options = isset($config['options']) ? $config['options'] : array();

        if (!is_array($options)) {
            throw new ServiceNotCreatedException(
                'Invalid value specified for "options"; must be an array'
            );
        }

        switch ($class) {
            case 'Zend\Session\SaveHandler\Cache':
                $factory = new CacheSaveHandlerFactory();
                return $factory->createSaveHandler($serviceLocator, new CacheSaveHandlerOptions($options));
                break;

            default:
                throw new ServiceNotCreatedException(
                    sprintf('Unsupported save handler class "%s" specified in "class"', $class)
                );
                break;
        }
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    public function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        $config = $serviceLocator->get('config');

        if (isset($config[self::CONFIG_KEY])) {
            $this->config = $config[self::CONFIG_KEY];
        } else {
            $this->config = array();
        }

        return $this->config;
    }
}
