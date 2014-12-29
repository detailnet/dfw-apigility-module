<?php

namespace Application\Core\Normalizer;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class JMSSerializerBasedNormalizerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Application\Core\JMSSerializer\EventDispatcher\Subscriber\DoctrineProxySubscriber $doctrineProxySubscriber */
        $doctrineProxySubscriber = $serviceLocator->get(
            'Application\Core\JMSSerializer\EventDispatcher\Subscriber\DoctrineProxySubscriber'
        );

        /** @var \JMS\Serializer\EventDispatcher\EventDispatcher $eventDispatcher */
        $eventDispatcher = $serviceLocator->get('jms_serializer.event_dispatcher');
        $eventDispatcher->setListeners(array()); // Remove default listeners/subscribers
        $eventDispatcher->addSubscriber($doctrineProxySubscriber); // Add our own version of the default subscriber to support HalCollection types

        /** @var \JMS\Serializer\Serializer $serializer */
        $serializer = $serviceLocator->get('jms_serializer.serializer');

        return new JMSSerializerBasedNormalizer($serializer);
    }
}
