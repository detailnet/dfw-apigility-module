<?php

namespace Detail\Apigility\Rest\Resource;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Initializer\InitializerInterface;

use Detail\Apigility\Options\ModuleOptions;

class ResourceInitializer implements
    InitializerInterface
{
    /**
     * Initialize the given instance
     *
     * @param ContainerInterface $container
     * @param object $instance
     * @return void
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof BaseResourceListener) {
            /** @var ModuleOptions $moduleOptions */
            $moduleOptions = $container->get(ModuleOptions::CLASS);

            $requestCommandMap = $moduleOptions->getRequestCommandMap($instance);

            $instance->setRequestCommandMap($requestCommandMap);
        }
    }
}
