<?php

namespace Detail\Apigility\View;

use Countable;

use Zend\Paginator\Paginator;
use Zend\View\Renderer\JsonRenderer as BaseJsonRenderer;

use ZF\Hal\Collection as HalCollection;

use Detail\Normalization\Normalizer\NormalizerInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareTrait;
use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareInterface;
use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareTrait;
use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;

class JsonRenderer extends BaseJsonRenderer implements
    NormalizerAwareInterface,
    NormalizationGroupsProviderAwareInterface
{
    use NormalizerAwareTrait;
    use NormalizationGroupsProviderAwareTrait;

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

    public function render($nameOrModel, $values = null)
    {
        if (!$nameOrModel instanceof JsonModel) {
            return parent::render($nameOrModel, $values);
        }

        if ($nameOrModel->isEntity()) {
            /** @var \ZF\Hal\Entity $halEntity */
            $halEntity = $nameOrModel->getPayload();
            $entity = $halEntity->entity;
            $normalizationGroups = $this->getNormalizationGroups($halEntity);

            $payload = $this->getNormalizer()->normalize($entity, $normalizationGroups);

            return parent::render($payload);
        }

        if ($nameOrModel->isCollection()) {
            /** @var HalCollection $collection */
            $collection = $nameOrModel->getPayload();

            $payload = $this->getCollectionPayload($collection);

//            if ($payload instanceof ApiProblem) {
//                return $this->renderApiProblem($payload);
//            }
            return parent::render($payload);
        }

        return parent::render($nameOrModel, $values);
    }

    /**
     * @param HalCollection $halCollection
     * @return array
     */
    protected function getCollectionPayload(HalCollection $halCollection)
    {
        $collection = $halCollection->getCollection();
        $collectionName = $halCollection->getCollectionName();

        $attributes = $halCollection->getAttributes();

        $normalizationGroups = $this->getNormalizationGroups($halCollection);

        if ($collection instanceof Paginator) {
            $pageSize = (int) (isset($attributes['page_size']) ? $attributes['page_size'] : $halCollection->getPageSize());

            $collection->setItemCountPerPage($pageSize);
            $items = (array) $collection->getCurrentItems();

            /** @todo Force snake case as collection name? */
            $payload = array(
                $collectionName => $this->getNormalizer()->normalize($items, $normalizationGroups),
                'page_count' => (int) (isset($attributes['page_count']) ? $attributes['page_count'] : $collection->count()),
                'page_size' => $pageSize,
                'total_items' => (int) (isset($attributes['total_items']) ? $attributes['total_items'] : $collection->getTotalItemCount()),
            );
        } else {
            $payload = array(
                $collectionName => $this->getNormalizer()->normalize($collection, $normalizationGroups),
            );

            if (is_array($collection) || $collection instanceof Countable) {
                $payload['total_items'] = isset($attributes['total_items']) ? $attributes['total_items'] : count($collection);
            }
        }

        $payload = array_merge($attributes, $payload);

        return $payload;
    }

    /**
     * @param mixed $object
     * @return array|null
     */
    protected function getNormalizationGroups($object)
    {
        $groupsProvider = $this->getNormalizationGroupsProvider();

        if ($groupsProvider === null) {
            return null;
        }

        return $groupsProvider->getGroups($object);
    }
}
