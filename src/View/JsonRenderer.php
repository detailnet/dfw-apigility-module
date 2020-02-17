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
    use NormalizerBasedRendererTrait {
        getNormalizationGroups as private getObjectNormalizationGroups;
    }

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

        $payload = $this->normalizeEntityOrCollection($nameOrModel);

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
        if ($normalizationGroups === null) {
            $this->normalizationGroups = null;

            return;
        }

        // First assign the group provider's default groups
        $this->normalizationGroups = $this->getNormalizationGroupsProvider()->getDefaultGroups();

        foreach ($normalizationGroups as $normalizationGroup) {
            $this->normalizationGroups[] = (string) $normalizationGroup;
        }
    }

    /**
     * @param mixed $object
     * @return array|null
     */
    protected function getNormalizationGroups($object)
    {
        // Own normalization groups take precedence over group-provider defined ones
        return $this->normalizationGroups ?? $this->getObjectNormalizationGroups($object);
    }
}
