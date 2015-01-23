<?php

namespace Application\Core\Aws\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class S3ClientFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Aws\Common\Aws $aws */
        $aws = $serviceLocator->get('aws');

        $client = $aws->get('s3');

        return $client;
    }
}
