<?php

namespace Detail\Apigility\Factory\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\View\XmlStrategy;

class XmlStrategyFactory implements
    FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Apigility\View\XmlRenderer $renderer */
        $renderer = $serviceLocator->get('Detail\Apigility\View\XmlRenderer');

        $strategy = new XmlStrategy($renderer);

        return $strategy;
    }
}
