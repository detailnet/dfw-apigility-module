<?php

namespace Detail\Apigility\Normalization;

interface NormalizationGroupsProviderAwareInterface
{
    /**
     * @return NormalizationGroupsProviderInterface
     */
    public function getNormalizationGroupsProvider();

    /**
     * @param NormalizationGroupsProviderInterface $normalizationGroupsProvider
     */
    public function setNormalizationGroupsProvider(
        NormalizationGroupsProviderInterface $normalizationGroupsProvider
    );
}
