<?php

namespace Application\Core\Commanding\Factory\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\Core\Commanding\Listener\LoggingListener;

class LoggingListenerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $listener = new LoggingListener();

        return $listener;
    }
}
