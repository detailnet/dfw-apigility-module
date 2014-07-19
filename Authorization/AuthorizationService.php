<?php

namespace Application\Core\Authorization;

use ZfcRbac\Service as ZfcRbac;

class AuthorizationService implements AuthorizationServiceInterface
{
    protected $rbacService = null;

    public function __construct(ZfcRbac\AuthorizationServiceInterface $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function isAllowed($action, $context = null)
    {
        return $this->rbacService->isGranted($action, $context);
    }
}
