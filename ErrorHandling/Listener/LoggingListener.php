<?php

namespace Application\Core\ErrorHandling\Listener;

use Exception;

use Psr\Log\LogLevel;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

use ZF\ApiProblem\ApiProblemResponse;

use Detail\Log\Listener\BaseLoggingListener;

class LoggingListener extends BaseLoggingListener
{
    public function __construct()
    {
        $this->setLoggerPrefix('ErrorHandling');
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_FINISH, array($this, 'onFinish'), -10000 // We want to be last
        );
    }

    /**
     * Intercept ApiProblem
     *
     * @param MvcEvent $e
     */
    public function onFinish(MvcEvent $e)
    {
        $exception = $this->getException($e);

        if ($exception === null) {
            return;
        }

        $data = array(
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        );

        $this->log('Exception detected', LogLevel::ERROR, $data);
    }

    /**
     * @param MvcEvent $e
     * @return Exception
     */
    protected function getException(MvcEvent $e)
    {
        $exception = $e->getParam('exception');

        if ($exception !== null) {
            return $exception;
        }

        $response = $e->getResponse();

        if ($response instanceof ApiProblemResponse
            && $response->getApiProblem()->detail instanceof Exception
        ) {
            return $response->getApiProblem()->detail;
        }
    }
}
