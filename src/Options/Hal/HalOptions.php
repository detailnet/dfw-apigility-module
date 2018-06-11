<?php

namespace Detail\Apigility\Options\Hal;

use Zend\Stdlib\AbstractOptions;

class HalOptions extends AbstractOptions
{
    /**
     * @var string[]
     */
    protected $listeners = [];

    /**
     * @return string[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param string[] $listeners
     */
    public function setListeners(array $listeners)
    {
        $this->listeners = $listeners;
    }
}
