<?php

namespace Application\Core\Commanding\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\Core\Commanding\CommandDispatcher;
use Application\Core\Commanding\CommandHandlerManager;

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
