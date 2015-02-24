<?php

namespace Detail\Apigility\Hydrator;

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
     * @param array|string $groups
     * @param string|integer $version
     * @return array
     */
    public function extract($object, $groups = null, $version = null)
    {
        return $this->normalizer->normalize($object, $groups, $version);
    }

    /**
     * Hydrate object with the provided data.
     *
     * @param array $data
     * @param object $object
     * @param array|string $groups
     * @param string|integer $version
     * @return object
     */
    public function hydrate(array $data, $object, $groups = null, $version = null)
    {
        $class = get_class($object);

        return $this->normalizer->denormalize($data, $class, $groups, $version);
    }
}
