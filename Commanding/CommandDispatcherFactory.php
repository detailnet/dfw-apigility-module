<?php

namespace Application\Core\Commanding;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandDispatcherFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $commandHandlerManager = new CommandHandlerManager();
        $commandHandlerManager->setServiceLocator($serviceLocator);

        $commandDispatcher = new CommandDispatcher($commandHandlerManager);

        return $commandDispatcher;
    }
}
