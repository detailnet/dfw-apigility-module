<?php

namespace Application\Core\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class TestControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var \Application\User\Service\UserService $userService */
        $userService = $serviceLocator->get('Application\UserService\UserService');

        $controller = new TestController($userService);

        return $controller;
    }
}
