<?php

namespace Detail\Apigility\Rest\Resource;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\Rest\Resource as BaseResource;

use Detail\Apigility\Exception;

class Resource extends BaseResource
{
    public function patchMultiple($ids, $data)
    {
        if (!is_array($ids)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Data provided to %s must be an array of identifiers; received "%s"',
                    __FUNCTION__,
                    gettype($data)
                )
            );
        }

        if (is_array($data)) {
            $data = (object) $data;
        }

        if (!is_object($data)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Data provided to create must be either an array or object; received "%s"',
                    gettype($data)
                )
            );
        }

        $events = $this->getEventManager();
        $event = $this->prepareEvent(__FUNCTION__, array('ids' => $ids, 'data' => $data));

        $results = $events->triggerUntil(
            $event,
            function ($result) {
                return ($result instanceof ApiProblem || $result instanceof ApiProblemResponse);
            }
        );

        $last = $results->last();

        if (!is_array($last) && !is_object($last)) {
            return $data;
        }

        return $last;
    }
}
