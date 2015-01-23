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

class BaseResourceListener extends AbstractResourceListener implements
    CommandDispatcherAwareInterface,
    NormalizerAwareInterface
{
    use CommandDispatcherAwareTrait;
    use NormalizerAwareTrait;

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
