<?php

namespace Detail\Apigility\View;

interface AcceptsNormalizationGroups
{
    public function setNormalizationGroups(?array $groups): void;
}
