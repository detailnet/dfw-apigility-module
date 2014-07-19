<?php

namespace Application\Core\Authorization;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class AuthorizationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $rbacService = $serviceLocator->get('ZfcRbac\Service\AuthorizationService');
        /** @var \ZfcRbac\Service\AuthorizationService $domainService */

        $authorizationService = new AuthorizationService($rbacService);

        return $authorizationService;
    }
}
