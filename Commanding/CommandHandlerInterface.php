<?php

namespace Application\Core\Commanding;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}
