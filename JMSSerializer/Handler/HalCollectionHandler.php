<?php

namespace Application\Core\JMSSerializer\Handler;

use Doctrine\Common\Collections\Collection;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;

use Zend\View\Model\ModelInterface as ViewModel;

use ZF\Hal\Collection as HalCollection;
use ZF\Hal\View\HalJsonModel as HalJsonViewModel;

//use Application\Core\View\JsonModel as JsonViewModel;
use Zend\View\Model\JsonModel as JsonViewModel;

class HalCollectionHandler implements SubscribingHandlerInterface
{
    /**
     * @var ViewModel
     */
    protected $viewModel;

    public static function getSubscribingMethods()
    {
        $methods = array();
        $formats = array('php');

        /** @todo Make configurable */
        $collectionTypes = array(
            'ZF\Hal\Collection',
        );

        foreach ($collectionTypes as $type) {
            foreach ($formats as $format) {
                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'serializeCollection',
                );
            }
        }

        return $methods;
    }

    public function serializeCollection(VisitorInterface $visitor, Collection $collection, array $type, Context $context)
    {
        $viewModelClass = null;

        if ($this->viewModel !== null) {
            $viewModelClass = get_class($this->viewModel);
        }

        // Only serialize to HalCollection when:
        // 1. We're not rendering a view model (viewModel is NULL). So we are respecting the defined type.
        // 2. We're actually rendering to Hal.
        // Note that we're using the class name because other view models might inherit from HalJsonModel...
        if (/*$viewModelClass === null || */$viewModelClass == 'ZF\Hal\View\HalJsonModel') {
            return new HalCollection($collection->toArray());
        }

        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';

        return $visitor->visitArray($collection->toArray(), $type, $context);
    }

    public function injectViewModel(ViewModel $viewModel)
    {
        $this->viewModel = $viewModel;
    }
}
