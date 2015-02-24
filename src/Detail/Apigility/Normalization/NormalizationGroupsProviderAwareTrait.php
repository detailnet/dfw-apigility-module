<?php

namespace Detail\Apigility\Normalization;

trait NormalizationGroupsProviderAwareTrait
{
    /**
     * @var NormalizationGroupsProviderInterface
     */
    protected $normalizationGroupsProvider;

    /**
     * @return NormalizationGroupsProviderInterface
     */
    public function getNormalizationGroupsProvider()
    {
        return $this->normalizationGroupsProvider;
    }

    /**
     * @param NormalizationGroupsProviderInterface $normalizationGroupsProvider
     */
    public function setNormalizationGroupsProvider(
        NormalizationGroupsProviderInterface $normalizationGroupsProvider
    ) {
        $this->normalizationGroupsProvider = $normalizationGroupsProvider;
    }
}
