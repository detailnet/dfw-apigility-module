<?php

namespace Detail\Apigility\Factory\ContentValidation;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

use Detail\Apigility\ContentValidation\ContentValidationListener;

class ContentValidationListenerDelegatorFactory implements
    DelegatorFactoryInterface
{
    /**
     * Create ContentValidationListener
     *
     * @param ContainerInterface $container
     * @param string $name
     * @param callable $callback
     * @param array|null $options
     * @return ContentValidationListener
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $listener = $callback();

        return new ContentValidationListener($listener);
    }
}
