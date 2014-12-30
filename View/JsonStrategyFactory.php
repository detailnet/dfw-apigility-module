<?php

namespace Application\Core\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class JsonStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Application\Core\View\JsonRenderer $renderer */
        $renderer = $serviceLocator->get('Application\Core\View\JsonRenderer');

        $strategy = new JsonStrategy($renderer);

        return $strategy;
    }
}
