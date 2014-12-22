<?php

namespace Application\Core\Commanding;

use Zend\ServiceManager\AbstractPluginManager;

use Application\Core\Exception;

/**
 * Plugin manager implementation for command handlers.
 *
 * Enforces that adapters retrieved are instances of CommandHandlerInterface
 */
class CommandHandlerManager extends AbstractPluginManager
{
    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Default set of adapters
     *
     * @var array
     */
    protected $invokableClasses = array();

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof CommandHandlerInterface) {
            // We're okay
            return;
        }

        throw new Exception\RuntimeException(
            sprintf(
                'Plugin of type %s is invalid; must implement %s\CommandHandlerInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            )
        );
    }
}
