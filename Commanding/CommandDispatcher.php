<?php

namespace Application\Core\Commanding;

use ArrayObject;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

use Application\Core\Commanding\Command\CommandInterface;
use Application\Core\Commanding\Handler\CommandHandlerInterface;
use Application\Core\Exception\RuntimeException;

class CommandDispatcher implements
    CommandDispatcherInterface
{
    /**
     * @var CommandHandlerManager
     */
    protected $commandHandlers;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * @var array
     */
    protected $eventParams = array();

    /**
     * @param CommandHandlerManager $commandHandlers
     */
    public function __construct(CommandHandlerManager $commandHandlers)
    {
        $this->commandHandlers = $commandHandlers;
    }

    /**
     * Retrieve the event manager instance.
     *
     * Lazy-initializes one if none present.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    /**
     * Set the event manager instance.
     *
     * @param EventManagerInterface $events
     * @return self
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(
            array(
                __CLASS__,
                get_class($this),
                __NAMESPACE__ . '\CommandDispatcherInterface'
            )
        );

        $this->events = $events;
        return $this;
    }

    /**
     * @param array $params
     * @return self
     */
    public function setEventParams(array $params)
    {
        $this->eventParams = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getEventParams()
    {
        return $this->eventParams;
    }

    public function register($commandName, $commandHandler)
    {
        if ($commandHandler instanceof CommandHandlerInterface) {
            $this->commandHandlers->setService($commandName, $commandHandler);
        } else {
            $this->commandHandlers->setFactory($commandName, $commandHandler);
        }
    }

    public function handle(CommandInterface $command)
    {
        $commandName = $this->getCommandName($command);

        if (!$this->commandHandlers->has($commandName)) {
            throw new RuntimeException(
                sprintf('No handler registered for command "%s"', $commandName)
            );
        }

        $preEventParams = array(
            CommandDispatcherEvent::PARAM_COMMAND_NAME => $commandName,
            CommandDispatcherEvent::PARAM_COMMAND => $command,
        );

        $events = $this->getEventManager();

        $preEvent = $this->prepareEvent(CommandDispatcherEvent::EVENT_PRE_HANDLE, $preEventParams);
        $events->triggerUntil($preEvent, function ($result) {
            // Don't handle the command when a listener returns false
            return ($result === false);
        });

        $commandHandler = $this->commandHandlers->get($commandName);
        $commandHandlerResult = $commandHandler->handle($command);

        $postEventParams = array_merge(
            $preEventParams,
            array(
                CommandDispatcherEvent::PARAM_RESULT => $commandHandlerResult
            )
        );

        $postEvent = $this->prepareEvent(CommandDispatcherEvent::EVENT_HANDLE, $postEventParams);
        $events->trigger($postEvent);

        return $commandHandlerResult;
    }

    protected function getCommandName(CommandInterface $command)
    {
        $className = get_class($command);

        return $className;

//        $classNameParts = explode('\\', $className);
//
//        return $classNameParts[count($classNameParts) - 1];
    }

    protected function prepareEvent($name, array $params)
    {
        $event = new CommandDispatcherEvent($name, $this, $this->prepareEventParams($params));
//        $event->setQueryParams($this->getQueryParams());

        return $event;
    }

    /**
     * Prepare event parameters.
     *
     * Ensures event parameters are created as an array object, allowing them to be modified
     * by listeners and retrieved.
     *
     * @param  array $params
     * @return ArrayObject
     */
    protected function prepareEventParams(array $params)
    {
        $defaultParams = $this->getEventParams();
        $params = array_merge($defaultParams, $params);

        if (empty($params)) {
            return $params;
        }

        return $this->getEventManager()->prepareArgs($params);
    }
}
