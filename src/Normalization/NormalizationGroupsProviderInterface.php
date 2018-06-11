<?php

namespace Detail\Apigility\Normalization;

interface NormalizationGroupsProviderInterface
{
    /**
     * @param mixed $object
     * @return array
     */
    public function getGroups($object);
}
