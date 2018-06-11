<?php

namespace Detail\Apigility\Factory\View;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Apigility\View\JsonRenderer;
use Detail\Apigility\View\JsonStrategy;

class JsonStrategyFactory implements
    FactoryInterface
{
    /**
     * Create JsonStrategy
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return JsonStrategy
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var JsonRenderer $renderer */
        $renderer = $container->get(JsonRenderer::CLASS);

        $strategy = new JsonStrategy($renderer);

        return $strategy;
    }
}
