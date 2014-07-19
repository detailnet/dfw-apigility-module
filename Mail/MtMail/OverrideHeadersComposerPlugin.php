<?php

namespace Application\Core\Mail\MtMail;

use MtMail\ComposerPlugin\PluginInterface;
use MtMail\Event\ComposerEvent;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;

class OverrideHeadersComposerPlugin extends AbstractListenerAggregate implements PluginInterface
{
    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @param ComposerEvent $event
     */
    public function injectOverrideHeaders(ComposerEvent $event)
    {
        $message = $event->getMessage();

        foreach ($this->headers as $header => $value) {
            $messageHeaders = $message->getHeaders();

            // Remove all previously set headers of the same type before adding our own
            $messageHeaders->removeHeader($header);

            if ($value) {
                $messageHeaders->addHeaderLine($header, $value);
            }
        }
    }

    /**
     * Attach listener.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            ComposerEvent::EVENT_HEADERS_POST,
            array($this, 'injectOverrideHeaders'),
            -100 // The priority needs to be lower than the other plugins
        );
    }

    /**
     * @param  array $headers
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
