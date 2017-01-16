<?php

namespace Detail\Apigility\View;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\View\ViewEvent;

class ImageStrategy extends AbstractListenerAggregate
{
    /**
     * @var ImageRenderer
     */
    protected $renderer;

    /**
     * @param ImageRenderer $renderer
     */
    public function __construct(ImageRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }

    /**
     * Detect if we should use the ImageRenderer based on model type
     *
     * @param  ViewEvent $event
     * @return null|ImageRenderer
     */
    public function selectRenderer(ViewEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof ImageModel) {
            // no ImageModel; do nothing
            return null;
        }

        // ImageModel found
        return $this->renderer;
    }

    /**
     * Inject the response with the image payload and appropriate Content-Type header
     *
     * @param ViewEvent $event
     * @return void
     */
    public function injectResponse(ViewEvent $event)
    {
        $renderer = $event->getRenderer();

        if ($renderer !== $this->renderer) {
            // Discovered renderer is not ours; do nothing
            return;
        }

        $result = $event->getResult();

        if (!is_string($result)) {
            // We don't have a string, and thus, no image
            return;
        }

        // Populate response
        /** @var \Zend\Http\Response $response */
        $response = $event->getResponse();
        $response->setContent($result);
        $headers = $response->getHeaders();

        $contentType = 'image/png';

        $headers->addHeaderLine('content-type', $contentType);
    }
}
