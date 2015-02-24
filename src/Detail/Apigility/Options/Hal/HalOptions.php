<?php

namespace Detail\Apigility\Options\Hal;

use Detail\Core\Options\AbstractOptions;

class HalOptions extends AbstractOptions
{
    /**
     * @var string[]
     */
    protected $listeners = array();

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
