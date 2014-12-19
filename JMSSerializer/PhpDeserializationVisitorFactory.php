<?php

namespace Application\Core\JMSSerializer;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class PhpDeserializationVisitorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PhpDeserializationVisitor(
            $serviceLocator->get('jms_serializer.naming_strategy'),
            $serviceLocator->get('jms_serializer.object_constructor')
        );
    }
}
