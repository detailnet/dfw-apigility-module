<?php

namespace Application\Core\Commanding\Service;

use Application\Core\Commanding\Command\CommandInterface;
use Application\Core\Commanding\CommandDispatcherInterface;
use Application\Core\Exception\RuntimeException;

trait CommandDispatcherAwareTrait
{
    /**
     * @var CommandDispatcherInterface
     */
    protected $commands;

    /**
     * @return CommandDispatcherInterface
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param CommandDispatcherInterface $commands
     */
    public function setCommands(CommandDispatcherInterface $commands)
    {
        $this->commands = $commands;
    }

    protected function handleCommand(CommandInterface $command)
    {
        $commandDispatcher = $this->getCommands();

        if ($commandDispatcher === null) {
            throw new RuntimeException(
                'A command dispatcher needs to be injected before commands can be handled'
            );
        }

        return $commandDispatcher->handle($command);
    }
}
