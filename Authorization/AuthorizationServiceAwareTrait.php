<?php

namespace Application\Core\Authorization;

/**
 * Makes a class AuthorizationService aware.
 */
trait AuthorizationServiceAwareTrait
{
    /**
     * The AuthorizationService
     *
     * @var AuthorizationServiceInterface
     */
    protected $authorizationService;

    /**
     * Set the AuthorizationService
     *
     * @param AuthorizationServiceInterface $authorizationService
     * @return void
     */
    public function setAuthorizationService(AuthorizationServiceInterface $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Return the AuthorizationService
     *
     * @return AuthorizationServiceInterface
     */
    public function getAuthorizationService()
    {
        return $this->authorizationService;
    }
}
