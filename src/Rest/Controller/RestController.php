<?php

namespace Detail\Apigility\Rest\Controller;

use Countable;

use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel as ContentNegotiationViewModel;
use ZF\Hal\Collection as HalCollection;
use ZF\Hal\Entity as HalEntity;
use ZF\Rest\RestController as BaseRestController;

use Detail\Apigility\Exception;
use Detail\Apigility\Rest\Resource\Resource;

class RestController extends BaseRestController
{
    public function onDispatch(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();

        /** @var \Zend\Http\Request $request */
        $request = $event->getRequest();
        $method = strtolower($request->getMethod());

        if ($routeMatch && $method === 'patch') {
            $id = $this->getIdentifier($routeMatch, $request);
            $data = $this->processBodyContent($request);

            // We assume we have to patch multiple resources when
            // there's a comma (the separator) in the ID.
            if ($id !== false && strpos($id, ',') !== false) {
                $ids = explode(',', $id);
                $return = $this->patchMultiple($ids, $data);

                $routeMatch->setParam('action', 'patchMultiple');
                $event->setResult($return);

                return $this->processResult($event, $return);
            }
        }

        return parent::onDispatch($event);
    }

    public function patchMultiple($ids, $data)
    {
        $resource = $this->getResource();

        if (!$resource instanceof Resource) {
            throw new Exception\DomainException(
                sprintf(
                    'Resource of type %s does not support patching multiple resources at once; ' .
                    'Use or subclass %s to support it',
                    get_class($resource),
                    Resource::CLASS
                )
            );
        }

        $eventData = ['ids' => $ids, 'data' => $data];

        $events = $this->getEventManager();
        $events->trigger('patchMultiple.pre', $this, $eventData);

        try {
            $collection = $resource->patchMultiple($ids, $data);
        } catch (\Exception $e) {
            return new ApiProblem($this->getHttpStatusCodeFromException($e), $e);
        }

        if ($collection instanceof ApiProblem
            || $collection instanceof ApiProblemResponse
        ) {
            return $collection;
        }

        $pageSize = -1;

        if ($collection instanceof Paginator) {
            $pageSize = $collection->getCurrentItemCount();
        } elseif (is_array($collection) || $collection instanceof Countable) {
            $pageSize = count($collection);
        }

        /** @var \ZF\Hal\Plugin\Hal $plugin */
        $plugin = $this->plugin('Hal');
        $collection = $plugin->createCollection($collection, $this->route);
        $collection->setCollectionRoute($this->route);
        $collection->setRouteIdentifierName($this->getRouteIdentifierName());
        $collection->setEntityRoute($this->route);
//        $collection->setPage($this->getRequest()->getQuery('page', 1));
//        $collection->setPageSize($this->getPageSize());
        $collection->setPage(1);
        $collection->setPageSize($pageSize);
        $collection->setCollectionName($this->collectionName);

        $eventData['collection'] = $collection;

        $events->trigger('patchMultiple.post', $this, $eventData);
        return $collection;
    }

    protected function processResult(MvcEvent $event, $return)
    {
        if ($return instanceof ApiProblem) {
            return new ApiProblemResponse($return);
        }

        if (!$return instanceof HalEntity
            && !$return instanceof HalCollection
        ) {
            return $return;
        }

        // Set the fallback content negotiation to use HalJson.
        $event->setParam('ZFContentNegotiationFallback', 'HalJson');

        // Use content negotiation for creating the view model
        $viewModel = new ContentNegotiationViewModel(['payload' => $return]);
        $viewModel->setTerminal(true);
        $event->setResult($viewModel);
        return $viewModel;
    }
}
