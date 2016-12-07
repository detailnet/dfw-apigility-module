<?php

namespace Detail\Apigility\View;

use Zend\View\Model\ModelInterface;
use Zend\View\Renderer\JsonRenderer as BaseJsonRenderer;

use Detail\Normalization\Normalizer\NormalizerInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;

use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareInterface;
use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;

class JsonRenderer extends BaseJsonRenderer implements
    NormalizerAwareInterface,
    NormalizationGroupsProviderAwareInterface
{
    use NormalizerBasedRendererTrait;

    /**
     * @param NormalizerInterface $normalizer
     * @param NormalizationGroupsProviderInterface $normalizationGroupsProvider
     */
    public function __construct(
        NormalizerInterface $normalizer,
        NormalizationGroupsProviderInterface $normalizationGroupsProvider = null
    ) {
        $this->setNormalizer($normalizer);

        if ($normalizationGroupsProvider !== null) {
            $this->setNormalizationGroupsProvider($normalizationGroupsProvider);
        }
    }

    /**
     * @param ModelInterface|string $nameOrModel
     * @param array|null| $values
     * @return string
     */
    public function render($nameOrModel, $values = null)
    {
        if (!$nameOrModel instanceof JsonModel) {
            return parent::render($nameOrModel, $values);
        }

        $payload = $this->normalizeEntityOrCollection($nameOrModel);

        if ($payload !== null) {
            return parent::render($payload);
        }

        return parent::render($nameOrModel, $values);
    }
}
