<?php

namespace Detail\Apigility;

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
//use Zend\Mvc\MvcEvent;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ControllerProviderInterface,
    ServiceProviderInterface
{
//    public function onBootstrap(MvcEvent $event)
//    {
//        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
//        $serviceManager = $event->getApplication()->getServiceManager();
//
//        // Register our own normalizer based hydrator with Apigility/Hal's plugin manager so that
//        // the default hydrator can be found.
//        if ($serviceManager->has('ZF\Hal\MetadataMap')) {
//            $hydratorClass = __NAMESPACE__ . '\ZendHydrator\NormalizerBasedHydrator';
//
//            /** @var \ZF\Hal\Metadata\MetadataMap $metadataMap */
//            $metadataMap = $serviceManager->get('ZF\Hal\MetadataMap');
//            $metadataMap->getHydratorManager()->setFactory(
//                $hydratorClass,
//                function() use ($serviceManager, $hydratorClass) {
//                    return $serviceManager->get($hydratorClass);
//                }
//            );
//        }
//    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array();
    }

    public function getServiceConfig()
    {
        return array();
    }
}
