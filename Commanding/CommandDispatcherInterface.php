<?php

namespace Application\Core\Commanding;

use Application\Core\Commanding\Command\CommandInterface;

interface CommandDispatcherInterface
{
    public function register($commandName, $commandHandler);

    public function handle(CommandInterface $command);
}
