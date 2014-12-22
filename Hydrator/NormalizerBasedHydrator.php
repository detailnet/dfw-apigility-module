<?php

namespace Application\Core\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;

use Application\Core\Normalizer\NormalizerInterface;

class NormalizerBasedHydrator implements
    HydratorInterface
{

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        return $this->normalizer->normalize($object);
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $class = get_class($object);

        return $this->normalizer->denormalize($data, $class);
    }
}
