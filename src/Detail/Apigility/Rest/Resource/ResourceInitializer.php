<?php

namespace Detail\Apigility\Rest\Resource;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class ResourceInitializer implements
    InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof BaseResourceListener) {
            if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            /** @var \Detail\Apigility\Options\ModuleOptions $moduleOptions */
            $moduleOptions = $serviceLocator->get('Detail\Apigility\Options\ModuleOptions');

            $requestCommandMap = $moduleOptions->getRequestCommandMap($instance);

            $instance->setRequestCommandMap($requestCommandMap);
        }
    }
}
