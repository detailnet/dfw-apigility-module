<?php

namespace Detail\Apigility\Rest\Resource;

use Zend\Stdlib\Parameters;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

use Detail\Commanding\Command\CommandInterface;
use Detail\Commanding\CommandDispatcherInterface;
use Detail\Commanding\Service\CommandDispatcherAwareInterface;
use Detail\Commanding\Service\CommandDispatcherAwareTrait;
use Detail\Normalization\Normalizer\NormalizerInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareTrait;

use Detail\Apigility\Exception;

class BaseResourceListener extends AbstractResourceListener implements
    CommandDispatcherAwareInterface,
    NormalizerAwareInterface
{
    use CommandDispatcherAwareTrait;
    use NormalizerAwareTrait;

    protected $requestCommandMap = array();

    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * @param NormalizerInterface $normalizer
     * @param CommandDispatcherInterface $commands
     */
    public function __construct(NormalizerInterface $normalizer, CommandDispatcherInterface $commands = null)
    {
        $this->setNormalizer($normalizer);

        if ($commands !== null) {
            $this->setCommands($commands);
        }
    }

    /**
     * @param string $eventName
     * @return bool
     */
    public function hasRequestCommandMapping($eventName)
    {
        $map = $this->getRequestCommandMap();

        return isset($map[$eventName]);
    }

    /**
     * @param string $eventName
     * @return array|string
     */
    public function getRequestCommandMapping($eventName)
    {
        $map = $this->getRequestCommandMap();

        return isset($map[$eventName]) ? $map[$eventName] : null;
    }

    /**
     * @return array|string
     */
    public function getRequestCommandMap()
    {
        return $this->requestCommandMap;
    }

    /**
     * @param array $requestCommandMap
     */
    public function setRequestCommandMap(array $requestCommandMap)
    {
        $this->requestCommandMap = $requestCommandMap;
    }

    /**
     * @param boolean $failOnNull
     * @return CommandInterface
     */
    public function getDispatchedCommand($failOnNull = true)
    {
        if ($this->command === null && $failOnNull !== false) {
            throw new Exception\RuntimeException('No command was created during dispatch');
        }

        return $this->command;
    }

    /**
     * @inheritdoc
     */
    public function dispatch(ResourceEvent $event)
    {
        $this->event = $event;

        switch ($event->getName()) {
            case 'fetchAll':
                // Always transform paging related params and decode JSON encoded params
                $event->setQueryParams(
                    new Parameters($this->getQueryParams($event, true, true))
                );
                break;
            default:
                // Do nothing
                break;
        }

        if ($this->hasRequestCommandMapping($event->getName())) {
            $this->command = $this->createCommand($event);
        }

        /** @todo Use eventing... */
        $result = $this->onBeforeDispatch($event, $this->command);

        // No need to continue if we already encountered a problem...
        if ($result instanceof ApiProblem) {
            return $result;
        }

        return parent::dispatch($event);
    }

    /**
     * @param ResourceEvent $event
     * @param CommandInterface $command
     * @return mixed
     */
    protected function onBeforeDispatch(ResourceEvent $event, CommandInterface $command = null)
    {
    }

    /**
     * @param ResourceEvent $event
     * @return array
     */
    protected function getBodyParams(ResourceEvent $event)
    {
        $params = (array) $event->getParam('data', array());

        return $params;
    }

    /**
     * @param ResourceEvent $event
     * @param bool $translatePaging
     * @param bool $translateDecoded
     * @return array
     */
    protected function getQueryParams(
        ResourceEvent $event,
        $translatePaging = false,
        $translateDecoded = false
    ) {
        $params = (array) ($event->getQueryParams() ?: array());

        if ($translatePaging === true) {
            if (!isset($params['page'])) {
                $params['page'] = 1;
            }

            if (!isset($params['page_size'])) {
                /** @todo Get from settings (subscribe to getList.pre?) */
                $params['page_size'] = 10;
            }

            $params['limit'] = $params['page_size'];
            $params['offset'] = ($params['page'] - 1) * $params['page_size'];

            unset($params['page'], $params['page_size']);
        }

        if ($translateDecoded === true) {
            foreach ($params as $key => $value) {
                // Try to detect JSON...
                if (is_string($value) && in_array($value[0], array('[', '{'))) {
                    try {
                        $params[$key] = $this->decodeJson($value);
                    } catch (Exception\RuntimeException $e) {
                        // Do nothing (assume it wasn't JSON)
                    }
                }
            }
        }

        return $params;
    }

    /**
     * @param ResourceEvent $event
     * @return array
     */
    protected function getRouteParams(ResourceEvent $event)
    {
        $params = $event->getRouteMatch()->getParams();

        unset($params['controller']);

        return $params;
    }

    /**
     * @param ResourceEvent $event
     * @return CommandInterface
     */
    protected function createCommand(ResourceEvent $event)
    {
        $normalizer = $this->getNormalizer();

        if ($normalizer === null) {
            throw new Exception\ConfigException(
                'Cannot use request to command mapping; no Normalizer provided'
            );
        }

        $commandMapping = $this->getRequestCommandMapping($event->getName());

        if (!isset($commandMapping['command_class'])) {
            throw new Exception\ConfigException(
                sprintf(
                    'Invalid request to command mapping configuration for event "%s"',
                    $event->getName()
                )
            );
        }

        $commandClass = $commandMapping['command_class'];
        $data = array();

        switch ($event->getName()) {
            case 'create':
            case 'deleteList':
            case 'patch':
            case 'patchList':
            case 'replaceList':
            case 'update':
                $data = $this->getBodyParams($event);
                break;
            case 'fetchAll':
                /// Note that the paging related params are already transformed and JSON params are decoded...
                $data = $this->getQueryParams($event, false, false);
                break;
            default:
                // Do nothing
                break;
        }

        /** @todo The normalizer should know from which version to denormalize from */
        return $normalizer->denormalize($data, $commandClass);
    }

    /**
     * @param string $value
     * @return array
     */
    protected function decodeJson($value)
    {
        $data = json_decode($value, true);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $message = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $message = 'Unknown error';
                    break;
            }

            throw new Exception\RuntimeException(
                'Failed to decode JSON parameter; ' . $message
            );
        }

        return $data;
    }
}
