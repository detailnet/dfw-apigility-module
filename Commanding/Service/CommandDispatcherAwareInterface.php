<?php

namespace Application\Core\Commanding\Service;

use Application\Core\Commanding\CommandDispatcherInterface;

interface CommandDispatcherAwareInterface
{
    /**
     * @param CommandDispatcherInterface $commands
     */
    public function setCommands(CommandDispatcherInterface $commands);
}
