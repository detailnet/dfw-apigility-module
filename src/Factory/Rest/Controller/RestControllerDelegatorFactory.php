<?php

namespace Detail\Apigility\Factory\Rest\Controller;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

use Detail\Apigility\Rest\Controller\RestController;
use Detail\Apigility\Rest\Resource\Resource;

class RestControllerDelegatorFactory implements
    DelegatorFactoryInterface
{
    /**
     * Create RestController
     *
     * @param ContainerInterface $container
     * @param string $name
     * @param callable $callback
     * @param array|null $options
     * @return RestController
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $controller = $callback();

        if ($controller instanceof RestController) {
            $resource = new Resource();
            $resource->setEventManager($controller->getResource()->getEventManager());

            $controller->setResource($resource);
        }

        return $controller;
    }
}
