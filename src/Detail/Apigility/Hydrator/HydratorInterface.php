<?php

namespace Detail\Apigility\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface as BaseHydratorInterface;

interface HydratorInterface extends BaseHydratorInterface
{
    /**
     * Extract values from the provided object.
     *
     * @param object $object
     * @param array|string $groups
     * @param string|integer $version
     * @return array
     */
    public function extract($object, $groups = null, $version = null);

    /**
     * Hydrate object with the provided data.
     *
     * @param array $data
     * @param object $object
     * @param array|string $groups
     * @param string|integer $version
     * @return object
     */
    public function hydrate(array $data, $object, $groups = null, $version = null);
}
