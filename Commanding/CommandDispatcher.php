<?php

namespace Application\Core\Commanding;

class CommandDispatcher implements
    CommandDispatcherInterface
{
    /**
     * @var CommandHandlerManager
     */
    protected $commandHandlers;

    public function __construct(CommandHandlerManager $commandHandlers)
    {
        $this->commandHandlers = $commandHandlers;
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
        $commandHandler = $this->commandHandlers->get($commandName);

        return $commandHandler->handle($command);
    }

    protected function getCommandName(CommandInterface $command)
    {
        $className = get_class($command);

        return $className;

//        $classNameParts = explode('\\', $className);
//
//        return $classNameParts[count($classNameParts) - 1];
    }
}
