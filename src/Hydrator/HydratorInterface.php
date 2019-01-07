<?php

namespace Detail\Apigility\Hydrator;

use Zend\Hydrator\HydratorInterface as BaseHydratorInterface;

interface HydratorInterface extends BaseHydratorInterface
{
    /**
     * Extract values from the provided object.
     *
     * @param object $object
     * @return array
     */
    public function extract($object);

    /**
     * Hydrate object with the provided data.
     *
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, $object);
}
