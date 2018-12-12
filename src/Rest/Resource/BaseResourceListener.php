<?php

namespace Detail\Apigility\Rest\Resource;

use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\Parameters;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

use Detail\Commanding\Command\CommandInterface;
use Detail\Commanding\Command\CollectionCommandInterface;
use Detail\Commanding\CommandDispatcherInterface;
use Detail\Commanding\Service\CommandDispatcherAwareInterface;
use Detail\Commanding\Service\CommandDispatcherAwareTrait;
use Detail\Normalization\Normalizer\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\NormalizerAwareTrait;
use Detail\Normalization\Normalizer\NormalizerInterface;

use Detail\Apigility\Exception;

class BaseResourceListener extends AbstractResourceListener implements
    CommandDispatcherAwareInterface,
    NormalizerAwareInterface
{
    use CommandDispatcherAwareTrait;
    use NormalizerAwareTrait;

    /**
     * @var array
     */
    protected $requestCommandMap = [];

    /**
     * @var int
     */
    protected $pageSize = 10;

    /**
     * @var string
     */
    protected $pageSizeParam = 'page_size';

    /**
     * @var string
     */
    protected $pageParam = 'page';

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
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return string
     */
    public function getPageSizeParam()
    {
        return $this->pageSizeParam;
    }

    /**
     * @param string $pageSizeParam
     */
    public function setPageSizeParam($pageSizeParam)
    {
        $this->pageSizeParam = $pageSizeParam;
    }

    /**
     * @return string
     */
    public function getPageParam()
    {
        return $this->pageParam;
    }

    /**
     * @param string $pageParam
     */
    public function setPageParam($pageParam)
    {
        $this->pageParam = $pageParam;
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
     * Attach listeners for all Resource events
     *
     * @param EventManagerInterface $events
     * @param integer $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        parent::attach($events, $priority);

        $events->attach('patchMultiple', [$this, 'dispatch']);
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

        switch ($event->getName()) {
            case 'patchMultiple':
                $ids = $event->getParam('ids', []);
                $data = $event->getParam('data', []);
                return $this->patchMultiple($ids, $data);
            default:
                // Do nothing
                break;
        }

        return parent::dispatch($event);
    }

    /**
     * Patch (partial in-place update) multiple resources at once.
     *
     * This differs from patching a list, because the update is only applied
     * to specific resources and not the complete list.
     *
     * @param array $ids
     * @param array $data
     * @return array|ApiProblem
     */
    public function patchMultiple($ids, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for specific resources');
    }

    /**
     * @param ResourceEvent $event
     * @param CommandInterface $command
     * @return mixed
     */
    protected function onBeforeDispatch(ResourceEvent $event, CommandInterface $command = null)
    {
        return true;
    }

    /**
     * @param ResourceEvent $event
     * @return array
     */
    protected function getBodyParams(ResourceEvent $event)
    {
        $params = (array) $event->getParam('data', []);

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
        $params = (array) ($event->getQueryParams() ?: []);

        if ($translatePaging === true) {
            $params = $this->translatePagingParams($params);
        }

        if ($translateDecoded === true) {
            foreach ($params as $key => $value) {
                // Try to detect JSON...
                if (is_string($value) && strlen($value) > 0 && in_array($value[0], ['[', '{'])) {
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
     * @param array $params
     * @return array
     */
    protected function translatePagingParams(array $params)
    {
        $pageParam = $this->getPageParam();
        $pageSizeParam = $this->getPageSizeParam();

        $page = isset($params[$pageParam]) ? $params[$pageParam] : 1;
        $pageSize = isset($params[$pageSizeParam]) ? $params[$pageSizeParam] : $this->getPageSize();

        // Only define limit and offset when the page size it not set to unlimited
        if ($pageSize != -1) {
            $params['limit']  = $pageSize;
            $params['offset'] = ($page - 1) * $pageSize;

            unset($params[$pageParam], $params[$pageSizeParam]);
        }

        return $params;
    }

    /**
     * @param ResourceEvent $event
     * @return CommandInterface|CollectionCommandInterface
     */
    protected function createCommand(ResourceEvent $event)
    {
        $normalizer = $this->getNormalizer();

        if ($normalizer === null) {
            throw new Exception\RuntimeException(
                'Cannot use request to command mapping; no Normalizer provided'
            );
        }

        $commandMapping = $this->getRequestCommandMapping($event->getName());

        if (!isset($commandMapping['command_class'])) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Invalid request to command mapping configuration for event "%s"',
                    $event->getName()
                )
            );
        }

        $commandClass = $commandMapping['command_class'];
        $data = [];

        switch ($event->getName()) {
            case 'create':
            case 'deleteList':
            case 'patch':
            case 'patchList':
            case 'patchMultiple':
            case 'update':
            case 'replaceList':
                $data = $this->getBodyParams($event);

                // Return filtered data if input filter is present
                $inputFilter = $this->getInputFilter();

                // When there is no data, the input filter returns all fields with default value,
                // therefore do not use input filter when body params are empty
                if ($inputFilter !== null && !empty($data)) {
                    $data = $inputFilter->getValues();
                }
                break;
            case 'fetchAll':
                /// Note that the paging related params are already transformed and JSON params are decoded...
                $data = $this->getQueryParams($event, false, false);
                break;
            default:
                // Do nothing
                break;
        }

        if (is_a($commandClass, CollectionCommandInterface::CLASS, true)) {
            /** @var CollectionCommandInterface $collectionCommand */
            $collectionCommand = $commandClass::create();
            $collection = $normalizer->denormalize($data, 'array<' . $collectionCommand->getObjectClassName() . '>');
            $collectionCommand->setCollectionData($collection);

            return $collectionCommand;
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
