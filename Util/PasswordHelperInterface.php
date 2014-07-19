<?php

namespace Application\Core\Util;

interface PasswordHelperInterface
{
    /**
     * Fetch a random plain text password.
     *
     * @param integer $length The password length
     * @param string $chars The alphabet
     * @return string The password
     */
    public function fetch($length = 10, $chars = null);

    /**
     * Create a password hash for a given plain text password.
     *
     * @param string $password The password to hash
     * @return string The formatted password hash
     */
    public function create($password);

    /**
     * Verify a password hash against a given plain text password.
     *
     * @param string $password The password to hash
     * @param string $hash The supplied hash to validate
     * @return bool Does the password validate against the hash
     */
    public function verify($password, $hash);
}
