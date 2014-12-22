<?php

namespace Application\Core\Commanding;

use Application\Core\Exception;
use Application\Core\Query\Query;

abstract class BaseCommandHandler implements
    CommandHandlerInterface
{
    public function handle(CommandInterface $command)
    {
        $commandClass = $this->getCommandClass();

        if (!$command instanceof $commandClass) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Plugin of type %s is invalid; must implement %s\CommandHandlerInterface',
                    (is_object($command) ? get_class($command) : gettype($command)),
                    __NAMESPACE__
                )
            );
        }

        return $this->handleCommand($command);
    }

    protected function getQuery(array $params)
    {
        $query = Query::fromArray($params);

        return $query;
    }

    abstract protected function handleCommand(CommandInterface $command);

    abstract protected function getCommandClass();
}
