<?php

namespace Detail\Apigility\View;

use Countable;

use Zend\Paginator\Paginator;

use ZF\Hal\Collection as HalCollection;
use ZF\Hal\Entity as HalEntity;

use Detail\Apigility\Exception;
use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareTrait;
use Detail\Normalization\Normalizer\NormalizerAwareTrait;
use Detail\Normalization\Normalizer\SerializerInterface;

trait NormalizerBasedRendererTrait
{
    use NormalizerAwareTrait;
    use NormalizationGroupsProviderAwareTrait;

    /**
     * @return array|string|null
     */
    protected function normalizeEntityOrCollection(ModelInterface $model, ?array $normalizationGroups = null)
    {
        if ($model->isEntity()) {
            /** @var HalEntity $halEntity */
            $halEntity = $model->getPayload();
            $entity = $halEntity->getEntity();

            if ($normalizationGroups === null) {
                $normalizationGroups = $this->provideNormalizationGroupsFor($halEntity);
            }

            return $this->normalize($entity, $normalizationGroups);
        }

        if ($model->isCollection()) {
            /** @var HalCollection $collection */
            $collection = $model->getPayload();

            return $this->normalizeCollection($collection, $normalizationGroups);
        }

        return null;
    }

    /**
     * @return array|string
     */
    protected function normalizeCollection(HalCollection $halCollection, ?array $normalizationGroups = null)
    {
        $collection = $halCollection->getCollection();
        $collectionName = $halCollection->getCollectionName();
        $attributes = $halCollection->getAttributes();

        if ($normalizationGroups === null) {
            $normalizationGroups = $this->provideNormalizationGroupsFor($halCollection);
        }

        if ($collection instanceof Paginator) {
            $pageSize = (int) (isset($attributes['page_size']) ? $attributes['page_size'] : $halCollection->getPageSize());

            $collection->setItemCountPerPage($pageSize);
            $items = (array) $collection->getCurrentItems();

            /** @todo Force snake case as collection name? */
            $payload = [
                $collectionName => $this->normalize($items, $normalizationGroups),
                'page_count' => (int) (isset($attributes['page_count']) ? $attributes['page_count'] : $collection->count()),
                'page_size' => $pageSize,
                'total_items' => (int) (isset($attributes['total_items']) ? $attributes['total_items'] : $collection->getTotalItemCount()),
            ];
        } else {
            $payload = [
                $collectionName => $this->normalize($collection, $normalizationGroups),
            ];

            if (is_array($collection) || $collection instanceof Countable) {
                $payload['total_items'] = isset($attributes['total_items']) ? $attributes['total_items'] : count($collection);
            }
        }

        $payload = array_merge($attributes, $payload);

        return $payload;
    }

    /**
     * @param mixed $object
     * @param array|string|null $groups
     * @return array|string
     */
    protected function normalize($object, $groups = null)
    {
        $normalizer = $this->getNormalizer();
        $format = $this->getSerializationFormat();
        
        if ($format !== null) {
            if (!$normalizer instanceof SerializerInterface) {
                throw new Exception\RuntimeException(
                    sprintf(
                        'Rendering to format "%s" required a Normalizer with serialization capabilities;' .
                        'the given %s does not implement %s',
                        $format,
                        get_class($normalizer),
                        SerializerInterface::CLASS
                    )
                );
            }

            return $normalizer->serialize($object, $format, $groups);
        } else {
            return $normalizer->normalize($object, $groups);
        }
    }

    /**
     * @param mixed $object
     */
    protected function provideNormalizationGroupsFor($object): ?array
    {
        $groupsProvider = $this->getNormalizationGroupsProvider();

        if ($groupsProvider === null) {
            return null;
        }

        return $groupsProvider->getGroups($object);
    }

    /**
     * @return string|null
     */
    protected function getSerializationFormat()
    {
        return null;
    }
}
