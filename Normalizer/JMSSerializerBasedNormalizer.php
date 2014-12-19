<?php

namespace Application\Core\Normalizer;

use JMS\Serializer\Serializer;

class JMSSerializerBasedNormalizer implements
    NormalizerInterface
{
    protected $jmsSerializer;

    public function __construct(Serializer $jmsSerializer)
    {
        $this->jmsSerializer = $jmsSerializer;
    }

    public function denormalize(array $data, $class)
    {
        return $this->jmsSerializer->deserialize($data, $class, 'php');
    }

    public function normalize($object)
    {
        return $this->jmsSerializer->serialize($object, 'php');
    }
}
