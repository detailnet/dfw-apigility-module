<?php

namespace Application\Core\Commanding\Handler;

use Application\Core\Commanding\Command\CommandInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}
