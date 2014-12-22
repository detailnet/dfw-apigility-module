<?php

namespace Application\Core\Commanding;

interface CommandDispatcherInterface
{
    public function register($commandName, $commandHandler);

    public function handle(CommandInterface $command);
}
