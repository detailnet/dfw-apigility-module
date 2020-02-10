<?php

namespace Detail\Apigility\Normalization;

use ReflectionClass;

use ZF\Hal\Collection as HalCollection;
use ZF\Hal\Entity as HalEntity;

class NormalizationGroupsProviderByString implements
    NormalizationGroupsProviderInterface
{
    /**
     * Default groups.
     *
     * The "Default" group is always required so that properties without a group don't get excluded.
     *
     * @var string[]
     */
    protected $groups = ['Default'];

    /**
     * @param string[] $groups
     */
    public function __construct(array $groups)
    {
        foreach ($groups as $group) {
            $this->groups[] = $group;
        }
    }

    /**
     * @param mixed $object
     * @return array
     */
    public function getGroups($object)
    {
        return $this->groups;
    }
}
