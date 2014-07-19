<?php

namespace Application\Core\Session\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\Session\SaveHandler\Cache as CacheSaveHandler;
use Zend\Cache\Storage\StorageInterface as CacheStorage;

class CacheSaveHandlerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @throws ServiceNotCreatedException
     * @return CacheSaveHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator->has('Application\Core\Session\Service\CacheSaveHandlerOptions')) {
            /** @var CacheSaveHandlerOptions $options */
            $options = $serviceLocator->get('Application\Core\Session\Service\CacheSaveHandlerOptions');

            if (!$options instanceof CacheSaveHandlerOptions) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'Zend\Session\SaveHandler\Cache requires options of type %s; received "%s"',
                        'Application\Core\Session\Service\CacheSaveHandlerOptions',
                        (is_object($options) ? get_class($options) : gettype($options))
                    )
                );
            }
        } else {
            $options = new CacheSaveHandlerOptions();
        }

        return $this->createSaveHandler($serviceLocator, $options);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param CacheSaveHandlerOptions $options
     * @throws ServiceNotCreatedException
     * @return CacheSaveHandler
     */
    public function createSaveHandler(ServiceLocatorInterface $serviceLocator, CacheSaveHandlerOptions $options)
    {
        $cacheStorageServiceName = $options->getCacheStorage();

        if (!is_string($cacheStorageServiceName) || strlen($cacheStorageServiceName) == 0) {
            throw new ServiceNotCreatedException(
                'Zend\Session\SaveHandler\Cache requires a valid service name for configuration "cacheStorage"'
            );
        }

        if (!$serviceLocator->has($cacheStorageServiceName)) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Zend\Session\SaveHandler\Cache requires service "%s"; service does not exist',
                    $cacheStorageServiceName
                )
            );
        }

        $cacheStorage = $serviceLocator->get($cacheStorageServiceName);

        if (!$cacheStorage instanceof CacheStorage) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Zend\Session\SaveHandler\Cache requires service "%s" of type %s; received "%s"',
                    $cacheStorageServiceName,
                    'Zend\Cache\Storage\StorageInterface',
                    (is_object($cacheStorage) ? get_class($cacheStorage) : gettype($cacheStorage))
                )
            );
        }

        return new CacheSaveHandler($cacheStorage);
    }
}
