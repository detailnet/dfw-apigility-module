<?php

namespace Application\Core\Authorization;

interface AuthorizationServiceInterface
{
    public function isAllowed($action, $context = null);
}
