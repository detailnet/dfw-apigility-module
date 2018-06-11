<?php

namespace Detail\Apigility;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ModelInterface as ViewModel;

use ZF\Hal\Metadata\MetadataMap;
use ZF\Hal\Plugin\Hal;

use Detail\Apigility\Factory\Rest\Controller\RestControllerDelegatorFactory;
use Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler;
use Detail\Apigility\View\JsonStrategy;
use Detail\Apigility\View\XmlStrategy;

class Module implements
    ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $event)
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();

        /** @var Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceManager->get(Options\ModuleOptions::CLASS);

        $config = $serviceManager->get('Config');

        // We might need to alter some dependencies of the controller...
        if (array_key_exists('zf-rest', $config)
            && is_array($config['zf-rest'])
        ) {
            $controllers = array_keys($config['zf-rest']);

            foreach ($controllers as $controller) {
                $serviceManager->get('ControllerManager')->addDelegator(
                    $controller,
                    RestControllerDelegatorFactory::CLASS
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

            /** @var MetadataMap $metadataMap */
            $metadataMap = $serviceManager->get('ZF\Hal\MetadataMap');
            $metadataMap->getHydratorManager()->setFactory(
                $hydratorClass,
                function () use ($serviceManager, $hydratorClass) {
                    return $serviceManager->get($hydratorClass);
                }
            );
        }

        $viewHelperManager = $serviceManager->get('ViewHelperManager');

        if ($viewHelperManager->has('Hal')) {
            /** @var Hal $hal */
            $hal = $viewHelperManager->get('Hal');

            foreach ($moduleOptions->getHal()->getListeners() as $listenerClass) {
                /** @var ListenerAggregateInterface $listener */
                $listener = $serviceManager->get($listenerClass);

                // The HAL plugin's EventManager instance does not compose a SharedEventManager,
                // so we attach directly to it.
                $listener->attach($hal->getEventManager());
            }
        }

        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender'], 100);

        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(
            'Zend\Stdlib\DispatchableInterface',
            MvcEvent::EVENT_DISPATCH,
            [$this, 'onDispatch'],
            -10
        );
    }

    public function onDispatch(MvcEvent $event)
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();

        $halCollectionHandlerClass = HalCollectionHandler::CLASS;

        if ($serviceManager->has($halCollectionHandlerClass)) {
            /** @var HalCollectionHandler $halCollectionHandler */
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

        if ($result instanceof View\JsonModel) {
            // Register at high priority, to "beat" normal HalJson and Json strategies registered
            // via view manager
            $this->attachViewStrategy($event, JsonStrategy::CLASS, 300);
        }

        if ($result instanceof View\XmlModel) {
            $this->attachViewStrategy($event, XmlStrategy::CLASS);
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    protected function attachViewStrategy(MvcEvent $event, string $class, ?integer $priority = null): void
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $event->getTarget()->getServiceManager();

        /** @var \Zend\View\View $view */
        $view = $serviceManager->get('View');

        /** @var ListenerAggregateInterface $listener */
        $listener = $serviceManager->get($class);
        $listener->attach($view->getEventManager(), $priority !== null ? $priority : 100);
    }
}
