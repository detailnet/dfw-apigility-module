<?php

namespace Application\Core\Commanding\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class CommandDispatcherInitializer implements
    InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof CommandDispatcherAwareInterface) {
            if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            /** @var \Application\Core\Commanding\CommandDispatcher $commandDispatcher */
            $commandDispatcher = $serviceLocator->get('Application\Core\Commanding\CommandDispatcher');

            $instance->setCommands($commandDispatcher);
        }
    }
}
