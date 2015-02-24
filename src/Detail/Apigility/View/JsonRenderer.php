<?php

namespace Detail\Apigility\View;

use Countable;

use Zend\Paginator\Paginator;
use Zend\View\Renderer\JsonRenderer as BaseJsonRenderer;

use ZF\Hal\Collection as HalCollection;

use Detail\Normalization\Normalizer\NormalizerInterface;

class JsonRenderer extends BaseJsonRenderer
{
    protected $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function getNormalizer()
    {
        return $this->normalizer;
    }

    public function render($nameOrModel, $values = null)
    {
        if (!$nameOrModel instanceof JsonModel) {
            return parent::render($nameOrModel, $values);
        }

        if ($nameOrModel->isEntity()) {
            /** @var \ZF\Hal\Entity $halEntity */
            $halEntity = $nameOrModel->getPayload();

            $payload = $this->getNormalizer()->normalize($halEntity->entity);

            return parent::render($payload);
        }

        if ($nameOrModel->isCollection()) {
            $payload = $this->getCollectionPayload($nameOrModel->getPayload());

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

        if ($collection instanceof Paginator) {
            $pageSize = (int) (isset($attributes['page_size']) ? $attributes['page_size'] : $halCollection->getPageSize());

            $collection->setItemCountPerPage($pageSize);
            $items = (array) $collection->getCurrentItems();

            $payload = array(
                $collectionName => $this->getNormalizer()->normalize($items),
                'page_count' => (int) (isset($attributes['page_count']) ? $attributes['page_count'] : $collection->count()),
                'page_size' => $pageSize,
                'total_items' => (int) (isset($attributes['total_items']) ? $attributes['total_items'] : $collection->getTotalItemCount()),
            );
        } else {
            $payload = array(
                $collectionName => $this->getNormalizer()->normalize($collection),
            );

            if (is_array($collection) || $collection instanceof Countable) {
                $payload['total_items'] = isset($attributes['total_items']) ? $attributes['total_items'] : count($collection);
            }
        }

        $payload = array_merge($attributes, $payload);

        return $payload;
    }
}
