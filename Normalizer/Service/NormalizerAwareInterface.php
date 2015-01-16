<?php

namespace Application\Core\Normalizer\Service;

use Application\Core\Normalizer\NormalizerInterface;

interface NormalizerAwareInterface
{
    /**
     * @param NormalizerInterface $normalizer
     */
    public function setNormalizer(NormalizerInterface $normalizer);
}
