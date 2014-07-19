<?php

namespace Application\Core\Authorization;

use Exception;

use ZfcRbac\Exception\UnauthorizedExceptionInterface;

class NotAllowedException extends Exception implements UnauthorizedExceptionInterface
{
}
