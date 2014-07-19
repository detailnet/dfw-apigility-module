<?php

namespace Application\Core\Util;

use Zend\Crypt\Password\Bcrypt as BcryptPasswordHelper;
use Zend\Math\Rand;

class PasswordHelper extends BcryptPasswordHelper implements PasswordHelperInterface
{
    /**
     * Fetch a random plain text password.
     *
     * @param integer $length The password length, defaults to 10
     * @param string $chars The alphabet, defaults to Base64
     * @return string The password
     */
    public function fetch($length = 10, $chars = null)
    {
        return Rand::getString($length, $chars, true);
    }
}
