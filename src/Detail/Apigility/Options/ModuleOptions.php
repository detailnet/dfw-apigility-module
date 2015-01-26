<?php

namespace Detail\Apigility\Options;

use Detail\Core\Options\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $normalizer;

    /**
     * @var array
     */
    protected $requestCommandMap = array();

    /**
     * @return string
     */
    public function getNormalizer()
    {
        return $this->normalizer;
    }

    /**
     * @param string $normalizer
     */
    public function setNormalizer($normalizer)
    {
        $this->normalizer = $normalizer;
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
