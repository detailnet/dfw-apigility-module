<?php

namespace Detail\Apigility\Hal;

use Zend\EventManager\EventInterface;

class HydrationListener extends BaseRenderListener
{
    /**
     * Listener for the "renderCollection" event.
     *
     * @param EventInterface $event
     */
    public function onRenderCollection(EventInterface $event)
    {
//        var_dump(__FUNCTION__);
        // TODO: Implement onRenderCollection() method.
    }

    /**
     * Listener for the "renderCollection.entity" event
     *
     * @param EventInterface $event
     * @return void
     */
    public function onRenderCollectionEntity(EventInterface $event)
    {
//        var_dump(__FUNCTION__);
        // TODO: Implement onRenderCollectionEntity() method.
    }

    /**
     * Listener for the "renderEntity" event
     *
     * @param EventInterface $event
     */
    public function onRenderEntity(EventInterface $event)
    {
//        var_dump(__FUNCTION__);
        // TODO: Implement onRenderEntity() method.
    }
}
