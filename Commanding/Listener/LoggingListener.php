<?php

namespace Application\Core\Commanding\Listener;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LogLevel;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

use Detail\Log\Service\LoggerAwareTrait;

//use Application\Core\Commanding\Command\CommandInterface;
use Application\Core\Commanding\CommandDispatcherEvent;

class LoggingListener extends AbstractListenerAggregate implements
    LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct()
    {
        $this->setLoggerPrefix('Commanding');
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
//        $this->listeners[] = $events->attach(
//            CommandDispatcherEvent::EVENT_PRE_HANDLE, array($this, 'onPreHandle'), $priority
//        );

        $this->listeners[] = $events->attach(
            CommandDispatcherEvent::EVENT_HANDLE, array($this, 'onHandle'), $priority
        );
    }

//    public function onPreHandle(CommandDispatcherEvent $e)
//    {
//    }

    public function onHandle(CommandDispatcherEvent $e)
    {
        $commandName = $e->getParam(CommandDispatcherEvent::PARAM_COMMAND_NAME, 'unknown command');

//        /** @var CommandInterface $command */
//        $command = $e->getParam(CommandDispatcherEvent::PARAM_COMMAND);

        $this->log(sprintf('Command "%s" was handled', $commandName), LogLevel::DEBUG);
    }
}
