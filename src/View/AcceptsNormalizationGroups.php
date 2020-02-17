<?php

namespace Detail\Apigility\View;

interface AcceptsNormalizationGroups
{
    /**
     * @param string[]|null $groups
     */
    public function setNormalizationGroups(?array $groups): void;
}
