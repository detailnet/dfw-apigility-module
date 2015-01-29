<?php

namespace Detail\Apigility\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;

use Detail\Normalization\Normalizer\NormalizerInterface;

class NormalizerBasedHydrator implements
    HydratorInterface
{
    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * Extract values from the provided object.
     *
     * @param object $object
     * @return array
     */
    public function extract($object)
    {
        return $this->normalizer->normalize($object);
    }

    /**
     * Hydrate object with the provided data.
     *
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $class = get_class($object);

        return $this->normalizer->denormalize($data, $class);
    }
}
