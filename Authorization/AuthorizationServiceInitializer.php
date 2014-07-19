<?php

namespace Application\Core\Authorization;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizationServiceInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param mixed $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof AuthorizationServiceAwareInterface) {
            /** @var AuthorizationServiceInterface $authorizationService */
            $authorizationService = $serviceLocator->get('Application\Core\Authorization\AuthorizationService');
            $instance->setAuthorizationService($authorizationService);
        }
    }
}
