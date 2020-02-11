<?php

namespace Detail\Apigility\Normalization;

use function preg_replace;
use function strtolower;

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
            $this->groups[] = $this->sanitizeGroup($group);
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

    private function sanitizeGroup(string $group): string
    {
        /** @todo Support camelCase to snake_case conversion? */
        return preg_replace('/[^a-z0-9_.]/', '', strtolower($group));
    }
}
