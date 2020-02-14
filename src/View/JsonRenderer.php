<?php

namespace Detail\Apigility\View;

use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareInterface;
use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;
use Detail\Normalization\Normalizer\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\NormalizerInterface;
use Zend\View\Model\ModelInterface as ZendModelInterface;
use Zend\View\Renderer\JsonRenderer as BaseJsonRenderer;

class JsonRenderer extends BaseJsonRenderer implements
    AcceptsNormalizationGroups,
    NormalizerAwareInterface,
    NormalizationGroupsProviderAwareInterface
{
    use NormalizerBasedRendererTrait;

    /** @var string[]|null */
    private $normalizationGroups;

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
     * @param ZendModelInterface|string $nameOrModel
     * @param array|null| $values
     * @return string
     */
    public function render($nameOrModel, $values = null)
    {
        if (!$nameOrModel instanceof JsonModel) {
            return parent::render($nameOrModel, $values);
        }

        $payload = $this->normalizeEntityOrCollection($nameOrModel, $this->getNormalizationGroups());

        if ($payload !== null) {
            return parent::render($payload);
        }

        return parent::render($nameOrModel, $values);
    }

    /**
     * @param string[]|null $normalizationGroups
     */
    public function setNormalizationGroups(?array $normalizationGroups): void
    {
        $this->normalizationGroups = $normalizationGroups;
    }

    private function getNormalizationGroups(): ?array
    {
        if (!is_array($this->normalizationGroups)) {
            return null;
        }

        $groups = $this->normalizationGroups;

        if ($this->getNormalizationGroupsProvider() !== null) {
            $groups = array_unique(
                array_merge(
                    $this->getNormalizationGroupsProvider()->getDefaultGroups(),
                    $groups
                )
            );
        }

        return $groups;
    }
}
