<?php

namespace Application\Core\View;

use Zend\View\Strategy\JsonStrategy as BaseJsonStrategy;
use Zend\View\ViewEvent;

class JsonStrategy extends BaseJsonStrategy
{
    public function __construct(JsonRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();

        if (!$model instanceof JsonModel) {
            // no JsonModel; do nothing
            return null;
        }

        // JsonModel found
        return $this->renderer;
    }
}
