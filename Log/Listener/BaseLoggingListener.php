<?php

namespace Application\Core\Log\Listener;

use Psr\Log\LoggerAwareInterface;

use Zend\EventManager\AbstractListenerAggregate;

use Detail\Log\Service\LoggerAwareTrait;

abstract class BaseLoggingListener extends AbstractListenerAggregate implements
    LoggerAwareInterface
{
    use LoggerAwareTrait;
}
