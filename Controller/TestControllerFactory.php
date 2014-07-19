<?php

namespace Application\Core\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class TestControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $userService = $controllerManager->getServiceLocator()->get('Application\UserService\UserService');
        /** @var \Application\User\Service\UserService $userService */

        $controller = new TestController($userService);

        return $controller;
    }
}
