<?php

namespace Application\Core\View;

use Countable;

use Zend\Paginator\Paginator;
use Zend\View\Renderer\JsonRenderer as BaseJsonRenderer;

use Application\Core\Normalizer\NormalizerInterface;

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
            /** @var \ZF\Hal\Collection $halCollection */
            $halCollection = $nameOrModel->getPayload();
            $collection = $halCollection->getCollection();
            $collectionName = $halCollection->getCollectionName();

            $attributes = $halCollection->getAttributes();

            if ($collection instanceof Paginator) {
                $items = (array) $collection->getCurrentItems();

                $payload = array(
                    $collectionName => $this->getNormalizer()->normalize($items),
                    'page_count' => (int) (isset($attributes['page_count']) ? $attributes['page_count'] : $collection->count()),
                    'page_size' => (int) (isset($attributes['page_size']) ? $attributes['page_size'] : $halCollection->getPageSize()),
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

//            if ($payload instanceof ApiProblem) {
//                return $this->renderApiProblem($payload);
//            }
            return parent::render($payload);
        }

        return parent::render($nameOrModel, $values);
    }
}
