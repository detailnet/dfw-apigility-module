<?php

namespace Detail\Apigility\Factory\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\View\JsonStrategy;

class JsonStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Apigility\View\JsonRenderer $renderer */
        $renderer = $serviceLocator->get('Detail\Apigility\View\JsonRenderer');

        $strategy = new JsonStrategy($renderer);

        return $strategy;
    }
}
