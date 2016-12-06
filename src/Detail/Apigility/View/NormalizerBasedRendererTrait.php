<?php

namespace Detail\Apigility\View;

use Countable;

use Zend\Paginator\Paginator;

use ZF\Hal\Collection as HalCollection;

use Detail\Normalization\Normalizer\Service\NormalizerAwareTrait;
use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareTrait;

trait NormalizerBasedRendererTrait
{
    use NormalizerAwareTrait;
    use NormalizationGroupsProviderAwareTrait;

    /**
     * @param ModelInterface $model
     * @return array|null
     */
    protected function normalizeEntityOrCollection(ModelInterface $model)
    {
        if ($model->isEntity()) {
            /** @var \ZF\Hal\Entity $halEntity */
            $halEntity = $model->getPayload();
            $entity = $halEntity->entity;
            $normalizationGroups = $this->getNormalizationGroups($halEntity);

            return $this->getNormalizer()->normalize($entity, $normalizationGroups);
        }

        if ($model->isCollection()) {
            /** @var HalCollection $collection */
            $collection = $model->getPayload();

            return $this->normalizeCollection($collection);
        }

        return null;
    }

    /**
     * @param HalCollection $halCollection
     * @return array
     */
    protected function normalizeCollection(HalCollection $halCollection)
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
