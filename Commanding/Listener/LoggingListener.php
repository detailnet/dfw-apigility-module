<?php

namespace Application\Core\Commanding\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

use Application\Core\Commanding\CommandDispatcherEvent;

class LoggingListener extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            CommandDispatcherEvent::EVENT_PRE_HANDLE, array($this, 'onPreHandle'), $priority
        );

        $this->listeners[] = $events->attach(
            CommandDispatcherEvent::EVENT_HANDLE, array($this, 'onHandle'), $priority
        );
    }

    public function onPreHandle(CommandDispatcherEvent $e)
    {
//        var_dump('pre', $e);
    }

    public function onHandle(CommandDispatcherEvent $e)
    {
//        var_dump('post', $e);
    }
}
