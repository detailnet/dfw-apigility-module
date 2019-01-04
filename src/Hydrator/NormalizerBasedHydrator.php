<?php

namespace Detail\Apigility\Hydrator;

use Detail\Normalization\Normalizer\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\NormalizerAwareTrait;
use Detail\Normalization\Normalizer\NormalizerInterface;

class NormalizerBasedHydrator implements
    HydratorInterface,
    NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @var array|null
     */
    protected $defaultGroups;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->setNormalizer($normalizer);
    }

    /**
     * @return boolean
     */
    public function hasDefaultGroups()
    {
        return $this->getDefaultGroups() !== null;
    }

    /**
     * @return array|null
     */
    public function getDefaultGroups()
    {
        return $this->defaultGroups;
    }

    /**
     * @param array|null $defaultGroups
     */
    public function setDefaultGroups($defaultGroups)
    {
        $this->defaultGroups = $defaultGroups;
    }

    /**
     * Extract values from the provided object.
     *
     * @param object $object
     * @return array
     */
    public function extract(object $object): array
    {
        $groups = $this->getGroups();

        return $this->normalizer->normalize($object, $groups);
    }

    /**
     * Hydrate object with the provided data.
     *
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, object $object)
    {
        $class = get_class($object);
        $groups = $this->getGroups();

        return $this->normalizer->denormalize($data, $class, $groups);
    }

    /**
     * @param array|string $groups
     * @return array|null
     */
    protected function getGroups($groups = null)
    {
        if ($groups === null) {
            $groups = $this->getDefaultGroups();
        }

        return $groups;
    }
}
