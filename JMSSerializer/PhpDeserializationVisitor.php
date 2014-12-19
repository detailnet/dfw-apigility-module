<?php

namespace Application\Core\JMSSerializer;

use JMS\Serializer\GenericDeserializationVisitor;

class PhpDeserializationVisitor extends GenericDeserializationVisitor
{
    protected function decode($data)
    {
        return $data;
    }
}
