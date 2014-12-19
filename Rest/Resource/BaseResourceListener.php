<?php

namespace Application\Core\Rest\Resource;

use ZF\Rest\AbstractResourceListener;

use Application\Core\Normalizer\NormalizerInterface;

class BaseResourceListener extends AbstractResourceListener
{
    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @return NormalizerInterface
     */
    public function getNormalizer()
    {
        return $this->normalizer;
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->setNormalizer($normalizer);
    }
}
