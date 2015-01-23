<?php

namespace Detail\Apigility;

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface as ViewModel;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ControllerProviderInterface,
    ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $event)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();

        // Register our own normalizer based hydrator with Apigility/Hal's plugin manager so that
        // the default hydrator can be found.
        /** @todo Move NormalizerBasedHydrator to this library (Detail\Apigility\Hydrator\NormalizerBasedHydrator) */
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

        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 100);

        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(
            'Zend\Stdlib\DispatchableInterface',
            MvcEvent::EVENT_DISPATCH,
            array($this, 'onDispatch'),
            -10
        );
    }

    public function onDispatch(MvcEvent $event)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();

        $halCollectionHandlerClass = 'Detail\Normalization\JMSSerializer\Handler\HalCollectionHandler';

        if ($serviceManager->has($halCollectionHandlerClass)) {
            /** @var \Detail\Normalization\JMSSerializer\Handler\HalCollectionHandler $halCollectionHandler */
            $halCollectionHandler = $serviceManager->get($halCollectionHandlerClass);

            /** @var ViewModel|array|null|false $viewModel */
            $viewModel = $event->getResult();

            if ($viewModel instanceof ViewModel) {
                $halCollectionHandler->injectViewModel($viewModel);
            }
        }
    }

    public function onRender(MvcEvent $event)
    {
        $result = $event->getResult();

        if (!$result instanceof View\JsonModel) {
            return;
        }

        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager = $event->getTarget()->getServiceManager();

        /** @var \Zend\View\View $view */
        $view = $serviceManager->get('View');
        $eventManager = $view->getEventManager();

        // Register at high priority, to "beat" normal HalJson and Json strategies registered
        // via view manager
        $eventManager->attach($serviceManager->get('Detail\Apigility\View\JsonStrategy'), 300);
    }

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
