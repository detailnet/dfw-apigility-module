<?php

namespace Detail\Apigility\Normalization;

use ZF\Hal\Collection as HalCollection;
use ZF\Hal\Entity as HalEntity;

class NormalizationGroupsProvider implements
    NormalizationGroupsProviderInterface
{
    /**
     * Default groups.
     *
     * The "Default" group is always required so that properties without a group don't get excluded.
     *
     * @var string[]
     */
    protected $defaultGroups = array('Default');

    /**
     * @return string[]
     */
    public function getDefaultGroups()
    {
        return $this->defaultGroups;
    }

    /**
     * @param string[] $defaultGroups
     */
    public function setDefaultGroups($defaultGroups)
    {
        $this->defaultGroups = $defaultGroups;
    }

    /**
     * @param mixed $object
     * @return array
     */
    public function getGroups($object)
    {
        $groups = $this->getDefaultGroups();

        if ($object instanceof HalCollection) {
            $groups[] = $object->getCollectionName();
        } elseif ($object instanceof HalEntity) {
            $entity = $object->entity;
            $entityName = (new \ReflectionClass($entity))->getShortName();
            $groups[] = $this->snakeCase($entityName);
        }

        return $groups;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function snakeCase($name)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
    }
}
