<?php

namespace Application\Core\JMSSerializer\EventDispatcher\Subscriber;

use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\Subscriber\DoctrineProxySubscriber as JmsDoctrineProxySubscriber;

class DoctrineProxySubscriber extends JmsDoctrineProxySubscriber
{
    public function onPreSerialize(PreSerializeEvent $event)
    {
        $type = $event->getType();

        // A HalCollection type mustn't ever be serialized as ArrayCollection for Doctrine's
        // {*}Collection objects (see parent class).
        if ($type['name'] == 'ZF\Hal\Collection') {
            return;
        }

        parent::onPreSerialize($event);
    }
}
