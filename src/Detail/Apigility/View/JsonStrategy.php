<?php

namespace Detail\Apigility\View;

use Zend\View\Strategy\JsonStrategy as BaseJsonStrategy;
use Zend\View\ViewEvent;

class JsonStrategy extends BaseJsonStrategy
{
    /**
     * @param JsonRenderer $renderer
     */
    public function __construct(JsonRenderer $renderer)
    {
        parent::__construct($renderer);
    }

    /**
     * @param ViewEvent $event
     * @return JsonRenderer|null
     */
    public function selectRenderer(ViewEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof JsonModel) {
            // no JsonModel; do nothing
            return null;
        }

        // JsonModel found
        return $this->renderer;
    }
}
