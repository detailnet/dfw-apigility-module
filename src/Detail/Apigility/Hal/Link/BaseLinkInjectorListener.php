<?php

namespace Detail\Apigility\Hal\Link;

use Detail\Apigility\Hal\BaseRenderListener;

abstract class BaseLinkInjectorListener extends BaseRenderListener
{
    /**
     * @var string
     */
    protected $entityRegexTemplate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->entityRegexTemplate = $this->provideEntityRegexTemplate();
    }

    /**
     * Determine if an entity is of interest (e.g., needs links or url parameter injection).
     *
     * The goal of this method is to test an entity to see if it is of a given
     * type and if the current entity version is equal to or greater than a
     * given minimum version.
     *
     * The method accepts the entity, the "type" to check against (essentially
     * the service name), and the minimum version we're interested in.
     *
     * @param mixed $object
     * @param string $type
     * @return bool
     */
    protected function isObjectOfInterest($object, $type/*, $minVersion = null*/)
    {
        if (!is_object($object)) {
            return false;
        }

        $regex = sprintf($this->entityRegexTemplate, $type);

//        if (!preg_match($regex, get_class($object), $matches)) {
        if (!preg_match($regex, get_class($object))) {
            return false;
        }

//        if ($matches['version'] < $minVersion) {
//            return false;
//        }

        return true;
    }

    /**
     * Regex to match classes.
     *
     * @return string
     */
    abstract protected function provideEntityRegexTemplate();
}
