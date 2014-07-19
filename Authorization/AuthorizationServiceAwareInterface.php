<?php

namespace Application\Core\Authorization;

interface AuthorizationServiceAwareInterface
{
    public function setAuthorizationService(AuthorizationServiceInterface $authorizationService);
}
