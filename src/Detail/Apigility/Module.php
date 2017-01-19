<?php

namespace Detail\Apigility;

use Zend\EventManager\ListenerAggregateInterface;
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
    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();

        /** @var Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceManager->get(__NAMESPACE__ . '\Options\ModuleOptions');

        $config = $serviceManager->get('Config');

        // We might need to alter some dependencies of the controller...
        if (array_key_exists('zf-rest', $config)
            && is_array($config['zf-rest'])
        ) {
            $controllers = array_keys($config['zf-rest']);

            foreach ($controllers as $controller) {
                $serviceManager->get('ControllerManager')->addDelegator(
                    $controller,
                    __NAMESPACE__ . '\Factory\Rest\Controller\RestControllerDelegatorFactory'
                );
            }
        }

        // Register our own normalizer based hydrator with Apigility/Hal's plugin manager so that
        // the default hydrator can be found.
        if ($serviceManager->has('ZF\Hal\MetadataMap')
            && isset($config['zf-hal'])
            && isset($config['zf-hal']['renderer'])
            && isset($config['zf-hal']['renderer']['default_hydrator'])
        ) {
            $hydratorClass = $config['zf-hal']['renderer']['default_hydrator'];

            /** @var \ZF\Hal\Metadata\MetadataMap $metadataMap */
            $metadataMap = $serviceManager->get('ZF\Hal\MetadataMap');
            $metadataMap->getHydratorManager()->setFactory(
                $hydratorClass,
                function() use ($serviceManager, $hydratorClass) {
                    return $serviceManager->get($hydratorClass);
                }
            );
        }

        $viewHelperManager = $serviceManager->get('ViewHelperManager');

        if ($viewHelperManager->has('Hal')) {
            /** @var \ZF\Hal\Plugin\Hal $hal */
            $hal = $viewHelperManager->get('Hal');

            foreach ($moduleOptions->getHal()->getListeners() as $listenerClass) {
                /** @var ListenerAggregateInterface $listener */
                $listener = $serviceManager->get($listenerClass);

                // The HAL plugin's EventManager instance does not compose a SharedEventManager,
                // so we attach directly to it.
                $hal->getEventManager()->attachAggregate($listener);
            }
        }

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

    /**
     * @param MvcEvent $event
     */
    public function onDispatch(MvcEvent $event)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();

        $halCollectionHandlerClass = __NAMESPACE__ . '\JMSSerializer\Handler\HalCollectionHandler';

        if ($serviceManager->has($halCollectionHandlerClass)) {
            /** @var \Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler $halCollectionHandler */
            $halCollectionHandler = $serviceManager->get($halCollectionHandlerClass);

            /** @var ViewModel|array|null|false $viewModel */
            $viewModel = $event->getResult();

            if ($viewModel instanceof ViewModel) {
                $halCollectionHandler->injectViewModel($viewModel);
            }
        }
    }

    /**
     * @param MvcEvent $event
     */
    public function onRender(MvcEvent $event)
    {
        $result = $event->getResult();

        if ($result instanceof View\JsonModel) {
            // Register at high priority, to "beat" normal HalJson and Json strategies registered
            // via view manager
            $this->attachViewStrategy($event, __NAMESPACE__ . '\View\JsonStrategy', 300);
        }

        if ($result instanceof View\XmlModel) {
            $this->attachViewStrategy($event, __NAMESPACE__ . '\View\XmlStrategy');
        }
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

    /**
     * @param MvcEvent $event
     * @param string $class
     * @param integer $priority
     */
    protected function attachViewStrategy(MvcEvent $event, $class, $priority = 100)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
        $serviceManager = $event->getTarget()->getServiceManager();

        /** @var \Zend\View\View $view */
        $view = $serviceManager->get('View');
        $eventManager = $view->getEventManager();
        $eventManager->attach($serviceManager->get($class), $priority);
    }
}
