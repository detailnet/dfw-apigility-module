<?php

namespace Detail\Apigility\ContentValidation;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;

use ZF\ContentNegotiation\ParameterDataContainer;
use ZF\ContentValidation\ContentValidationListener as BaseContentValidationListener;

class ContentValidationListener implements
    ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @var BaseContentValidationListener
     */
    protected $wrappedListener;

    /**
     * @param BaseContentValidationListener $listener
     */
    public function __construct(BaseContentValidationListener $listener)
    {
        $this->wrappedListener = $listener;
    }

    /**
     * @param EventManagerInterface $events
     * @param integer $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        // Trigger after authentication/authorization and content negotiation
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], -650);
    }

    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            $events->detach($callback);

            unset($this->listeners[$index]);
        }
    }

    /**
     * @param MvcEvent $event
     * @return null|\ZF\ApiProblem\ApiProblemResponse
     */
    public function onRoute(MvcEvent $event)
    {
        $request = $event->getRequest();

        if ($request instanceof HttpRequest
            && $request->isPatch()
        ) {
            $dataContainer = $event->getParam('ZFContentNegotiationParameterData', false);

            if ($dataContainer instanceof ParameterDataContainer) {
                $data = $dataContainer->getBodyParams();

                if (empty($data)) {
                    // For patch, only the provided data is filtered and validated.
                    // So when no data was provided, we don't need to filter at all.
                    return null;
                }
            }
        }

        return $this->wrappedListener->onRoute($event);
    }
}
