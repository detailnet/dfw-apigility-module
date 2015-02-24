<?php

namespace Detail\Apigility\Options\Normalization;

use Detail\Core\Options\AbstractOptions;

class NormalizationOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $normalizer;

    /**
     * @var string
     */
    protected $groupsProvider;

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
     * @return string
     */
    public function getGroupsProvider()
    {
        return $this->groupsProvider;
    }

    /**
     * @param string $groupsProvider
     */
    public function setGroupsProvider($groupsProvider)
    {
        $this->groupsProvider = $groupsProvider;
    }
}
