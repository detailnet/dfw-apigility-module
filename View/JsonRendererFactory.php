<?php

namespace Application\Core\View;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class JsonRendererFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $helpers = $serviceLocator->get('ViewHelperManager');

        /** @todo Make normalizer service name configurable */
        /** @var \Application\Core\Normalizer\JMSSerializerBasedNormalizer $normalizer */
        $normalizer = $serviceLocator->get('Application\Core\Normalizer\JMSSerializerBasedNormalizer');

        $renderer = new JsonRenderer($normalizer);
//        $renderer->setHelperPluginManager($helpers);

        return $renderer;
    }
}
