<?php

namespace Detail\Apigility\Hal\Link;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

abstract class BaseLinkInjectorListener implements
    ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = array();

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
     * Attach events to the HAL plugin.
     *
     * This method attaches listeners to the renderEntity and renderCollection.entity
     * events of ZF\Hal\Plugin\Hal.
     *
     * @param EventManagerInterface $eventManager
     */
    public function attach(EventManagerInterface $eventManager)
    {
        $this->listeners[] = $eventManager->attach(
            'renderCollection',
            array($this, 'onRenderCollection')
        );

        $this->listeners[] = $eventManager->attach(
            'renderCollection.entity',
            array($this, 'onRenderCollectionEntity')
        );

        $this->listeners[] = $eventManager->attach(
            'renderEntity',
            array($this, 'onRenderEntity')
        );
    }

    /**
     * Detach events from the shared event manager.
     *
     * This method detaches listeners it has previously attached.
     *
     * @param EventManagerInterface $eventManager
     */
    public function detach(EventManagerInterface $eventManager)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($eventManager->detach($listener)) {
                unset($listener[$index]);
            }
        }
    }

    /**
     * Listener for the "renderCollection" event.
     *
     * @param EventInterface $event
     */
    public function onRenderCollection(EventInterface $event)
    {
    }

    /**
     * Listener for the "renderCollection.entity" event
     *
     * @param EventInterface $event
     * @return void
     */
    public function onRenderCollectionEntity(EventInterface $event)
    {
    }

    /**
     * Listener for the "renderEntity" event
     *
     * @param EventInterface $event
     */
    public function onRenderEntity(EventInterface $event)
    {
    }

    /**
     * Determine if an entity is of interest (e.g., needs links or url parameter injection).
     *
     * The goal of this method is to test an entity to see if it is of a given
     * type and if the current entity version is equal to or greater than a
     * given mimimum version.
     *
     * The method accepts the entity, the "type" to check against (essentially
     * the service name), and the minimum version we're interested in.
     *
     * @param mixed $object
     * @param string $type
     * @param int $minVersion
     * @return bool
     */
    protected function isObjectOfInterest($object, $type, $minVersion = null)
    {
        if (!is_object($object)) {
            return false;
        }

        $regex = sprintf($this->entityRegexTemplate, $type);

        if (!preg_match($regex, get_class($object), $matches)) {
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
