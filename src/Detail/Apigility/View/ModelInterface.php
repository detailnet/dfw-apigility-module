<?php

namespace Detail\Apigility\View;

interface ModelInterface
{
    /**
     * @return boolean
     */
    public function isEntity();

    /**
     * @return boolean
     */
    public function isCollection();

    /**
     * @return mixed
     */
    public function getPayload();
}
