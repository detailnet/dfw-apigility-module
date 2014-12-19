<?php

namespace Application\Core\JMSSerializer;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class PhpSerializationVisitorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PhpSerializationVisitor(
            $serviceLocator->get('jms_serializer.naming_strategy')
        );
    }
}
