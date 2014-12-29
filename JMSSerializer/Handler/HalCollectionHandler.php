<?php

namespace Application\Core\JMSSerializer\Handler;

use Doctrine\Common\Collections\Collection;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;

use ZF\Hal\Collection as HalCollection;

class HalCollectionHandler implements SubscribingHandlerInterface
{
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
        /** @todo We should be able to disable/ignore this handler on runtime to recursively serialize to array (use metadata?) */
        return new HalCollection($collection->toArray());

//        // We change the base type, and pass through possible parameters.
//        $type['name'] = 'array';
//
//        return $visitor->visitArray($collection->toArray(), $type, $context);
    }
}
