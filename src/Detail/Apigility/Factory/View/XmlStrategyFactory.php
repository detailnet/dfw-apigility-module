<?php

namespace Detail\Apigility\Factory\View;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Detail\Apigility\View\XmlRenderer;
use Detail\Apigility\View\XmlStrategy;

class XmlStrategyFactory implements
    FactoryInterface
{
    /**
     * Create XmlStrategy
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return XmlStrategy
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var XmlRenderer $renderer */
        $renderer = $container->get(XmlRenderer::CLASS);

        $strategy = new XmlStrategy($renderer);

        return $strategy;
    }
}
