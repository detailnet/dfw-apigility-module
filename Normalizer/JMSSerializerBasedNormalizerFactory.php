<?php

namespace Application\Core\Normalizer;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class JMSSerializerBasedNormalizerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \JMS\Serializer\Serializer $serializer */
        $serializer = $serviceLocator->get('jms_serializer.serializer');

        return new JMSSerializerBasedNormalizer($serializer);
    }
}
