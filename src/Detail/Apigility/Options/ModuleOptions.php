<?php

namespace Detail\Apigility\Options;

use Detail\Core\Options\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var Normalization\NormalizationOptions
     */
    protected $normalization;

    /**
     * @var Hal\HalOptions
     */
    protected $hal;

    /**
     * @var array
     */
    protected $requestCommandMap = array();

    /**
     * @return Normalization\NormalizationOptions
     */
    public function getNormalization()
    {
        return $this->normalization;
    }

    /**
     * @param array $normalization
     */
    public function setNormalization(array $normalization)
    {
        $this->normalization = new Normalization\NormalizationOptions($normalization);
    }

    /**
     * @return Hal\HalOptions
     */
    public function getHal()
    {
        return $this->hal;
    }

    /**
     * @param array $hal
     */
    public function setHal(array $hal)
    {
        $this->hal = new Hal\HalOptions($hal);
    }

    /**
     * @param \ZF\Rest\AbstractResourceListener|string
     * @return array
     */
    public function getRequestCommandMap($resourceListener = null)
    {
        $map = $this->requestCommandMap;

        if ($resourceListener !== null) {
            if (is_object($resourceListener)) {
                $resourceListener = get_class($resourceListener);
            }

            return isset($map[$resourceListener]) ? $map[$resourceListener] : array();
        }

        return $map;
    }

    /**
     * @param array $requestCommandMap
     */
    public function setRequestCommandMap(array $requestCommandMap)
    {
        $this->requestCommandMap = $requestCommandMap;
    }
}
