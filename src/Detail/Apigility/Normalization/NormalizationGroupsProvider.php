<?php

namespace Detail\Apigility\Normalization;

use ReflectionClass;

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
            // zf-hal:1.4.0 introduced a getter for entities and deprecated the access through the public property
            if (method_exists($object, 'getEntity')) {
                $entity = $object->getEntity();
            } else {
                $entity = $object->entity;
            }

            if (is_object($entity)) {
                /** @var object $entity */
                $entityName = $this->getEntityName($entity);
                $groups[] = $this->snakeCase($entityName);
            }
        }

        return $groups;
    }

    /**
     * Strip namespace from object's class name.
     *
     * Example: Foo\Bar => Bar
     *
     * @param object $entity
     * @return string
     */
    protected function getEntityName($entity)
    {
        $entityName = (new ReflectionClass($entity))->getShortName();

        return $entityName;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function snakeCase($name)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $name, $matches);

        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        $snakeName = implode('_', $ret);

        return $snakeName;
    }
}
