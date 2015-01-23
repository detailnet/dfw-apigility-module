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
}
