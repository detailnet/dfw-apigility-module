<?php

namespace Detail\Apigility\Factory\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\Apigility\View\ImageRenderer;

class ImageRendererFactory implements
    FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ImageRenderer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $renderer = new ImageRenderer();

        return $renderer;
    }
}
