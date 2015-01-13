<?php

namespace Application\Core\JMSSerializer\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\DateHandler as BaseDateHandler;

class DateHandler extends BaseDateHandler
{
    public static function getSubscribingMethods()
    {
        $methods = array();
        $formats = array('php');
        $types = array('DateTime', 'DateInterval');

        foreach ($formats as $format) {
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'type' => 'DateTime',
                'format' => $format,
            );

            foreach ($types as $type) {
                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'serialize' . $type,
                );
            }
        }

        return $methods;
    }
}
