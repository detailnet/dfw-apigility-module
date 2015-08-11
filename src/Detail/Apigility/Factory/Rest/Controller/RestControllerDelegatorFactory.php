<?php

namespace Detail\Apigility\Factory\Rest\Controller;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\Apigility\Rest\Controller\RestController;
use Detail\Apigility\Rest\Resource\Resource;

class RestControllerDelegatorFactory implements
    DelegatorFactoryInterface
{
    public function createDelegatorWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName,
        $callback
    ) {
        $controller = $callback();

        if ($controller instanceof RestController) {
            $resource = new Resource();
            $resource->setEventManager($controller->getResource()->getEventManager());

            $controller->setResource($resource);
        }

        return $controller;
    }
}
