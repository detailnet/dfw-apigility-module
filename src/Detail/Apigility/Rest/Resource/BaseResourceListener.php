<?php

namespace Detail\Apigility\Rest\Resource;

use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

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
     * @inheritdoc
     */
    public function dispatch(ResourceEvent $event)
    {
        $normalizer = $this->getNormalizer();

        $this->event = $event;

        $commandMapping = $this->getRequestCommandMapping($event->getName());

        if ($commandMapping !== null) {
            if ($normalizer === null) {
                throw new Exception\ConfigException(
                    'Cannot use request to command mapping; no Normalizer provided'
                );
            }

            if (!isset($commandMapping['command_class'])) {
                throw new Exception\ConfigException(
                    sprintf(
                        'Invalid request to command mapping configuration for event "%s"',
                        $event->getName()
                    )
                );
            }

            $commandClass = $commandMapping['command_class'];
            $paramSource = isset($commandMapping['param_source']) ? $commandMapping['param_source'] : 'body';

            switch ($paramSource) {
                case 'query':
                    $data = $this->getQueryParams($event, $event->getName() === 'fetchAll');
                    break;
                case 'body':
                default:
                    $data = $this->getBodyParams($event);
                    break;
            }

//            switch ($event->getName()) {
//                case 'create':
//                case 'deleteList':
//                case 'patch':
//                case 'patchList':
//                case 'replaceList':
//                case 'update':
//                    $data = (array) $event->getParam('data', array());
//                    break;
//                default:
//                    throw new Exception\RuntimeException(
//                        sprintf(
//                            'Request to command mapping does not support the event "%s"',
//                            $event->getName()
//                        )
//                    );
//            }

            /** @todo The normalizer should know from which version to denormalize from */
            $command = $normalizer->denormalize($data, $commandClass);

            switch ($paramSource) {
                case 'query':
                    $event->setQueryParams($command);
                    break;
                case 'body':
                default:
                    $event->setParam('data', $command);
                    break;
            }
        }

        return parent::dispatch($event);
    }

    /**
     * @param ResourceEvent $event
     * @return array
     */
    private function getBodyParams(ResourceEvent $event)
    {
        $params = (array) $event->getParam('data', array());

        return $params;
    }

    /**
     * @param ResourceEvent $event
     * @param bool $translatePaging
     * @return array
     */
    private function getQueryParams(ResourceEvent $event, $translatePaging = false)
    {
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

        $params = array_merge(
            $event->getRouteMatch()->getParams(),
            $params
        );

        unset($params['controller']);

        return $params;
    }
}
