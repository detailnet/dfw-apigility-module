<?php

namespace Application\Core\Rest\Resource;

use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

use Application\Core\Commanding\CommandDispatcherInterface;
use Application\Core\Normalizer\NormalizerInterface;

class BaseResourceListener extends AbstractResourceListener
{
    /**
     * @var CommandDispatcherInterface
     */
    protected $commands;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @return CommandDispatcherInterface
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param CommandDispatcherInterface $commands
     */
    public function setCommands(CommandDispatcherInterface $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return NormalizerInterface
     */
    public function getNormalizer()
    {
        return $this->normalizer;
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param CommandDispatcherInterface $commands
     * @param NormalizerInterface $normalizer
     */
    public function __construct(CommandDispatcherInterface $commands, NormalizerInterface $normalizer)
    {
        $this->setNormalizer($normalizer);
        $this->setCommands($commands);
    }

    /**
     * @inheritdoc
     */
    public function dispatch(ResourceEvent $event)
    {
        $this->event = $event;

        switch ($event->getName()) {
            case 'fetchAll':
                return $this->fetchAll($this->getParams($event));
            default:
                // Do nothing...
                break;
        }

        return parent::dispatch($event);
    }

    protected function getParams(ResourceEvent $event)
    {
        $params = (array) ($event->getQueryParams() ?: array());

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

        $params = array_merge(
            $event->getRouteMatch()->getParams(),
            $params
        );

        unset($params['controller']);

        return $params;
    }
}
