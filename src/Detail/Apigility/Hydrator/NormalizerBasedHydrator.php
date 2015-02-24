<?php

namespace Detail\Apigility\Hydrator;

use Detail\Normalization\Normalizer\NormalizerInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareTrait;

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
     * @param array|string $groups
     * @param string|integer $version
     * @return array
     */
    public function extract($object, $groups = null, $version = null)
    {
        $groups = $this->getGroups($groups);

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
        $groups = $this->getGroups($groups);

        return $this->normalizer->denormalize($data, $class, $groups, $version);
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
