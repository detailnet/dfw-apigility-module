<?php

namespace Detail\Apigility\Factory\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\View\ImageStrategy;

class ImageStrategyFactory implements
    FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ImageStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Apigility\View\ImageRenderer $renderer */
        $renderer = $serviceLocator->get('Detail\Apigility\View\ImageRenderer');

        $strategy = new ImageStrategy($renderer);

        return $strategy;
    }
}
