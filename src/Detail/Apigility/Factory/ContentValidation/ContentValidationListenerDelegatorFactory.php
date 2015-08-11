<?php

namespace Detail\Apigility\Factory\ContentValidation;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\Apigility\ContentValidation\ContentValidationListener;

class ContentValidationListenerDelegatorFactory implements
    DelegatorFactoryInterface
{
    public function createDelegatorWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName,
        $callback
    ) {
        $listener = $callback();

        return new ContentValidationListener($listener);
    }
}
