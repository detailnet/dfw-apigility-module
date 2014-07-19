<?php

namespace Application\Core\Session\Service;

use Zend\Stdlib\AbstractOptions;

class CacheSaveHandlerOptions extends AbstractOptions
{
    /**
     * CacheStorage.
     *
     * @var string
     */
    protected $cacheStorage = null;

    /**
     * @return string
     */
    public function getCacheStorage()
    {
        return $this->cacheStorage;
    }

    /**
     * @param string $cacheStorage
     * @return CacheSaveHandlerOptions Provides fluent interface
     */
    public function setCacheStorage($cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
        return $this;
    }
}
