<?php

namespace Application\Core\Commanding;

use Zend\EventManager\Event;
//use Zend\Stdlib\Parameters;

class CommandDispatcherEvent extends Event
{
    const EVENT_PRE_HANDLE = 'handle.pre';
    const EVENT_HANDLE     = 'handle';

//    /**
//     * @var null|Parameters
//     */
//    protected $queryParams;
//
//    /**
//     * @param Parameters $params
//     * @return self
//     */
//    public function setQueryParams(Parameters $params = null)
//    {
//        $this->queryParams = $params;
//        return $this;
//    }
//
//    /**
//     * @return null|Parameters
//     */
//    public function getQueryParams()
//    {
//        return $this->queryParams;
//    }
//
//    /**
//     * Retrieve a single query parameter by name
//     *
//     * If not present, returns the $default value provided.
//     *
//     * @param string $name
//     * @param mixed $default
//     * @return mixed
//     */
//    public function getQueryParam($name, $default = null)
//    {
//        $params = $this->getQueryParams();
//        if (null === $params) {
//            return $default;
//        }
//
//        return $params->get($name, $default);
//    }
}
