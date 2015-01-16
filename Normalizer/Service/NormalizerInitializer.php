<?php

namespace Application\Core\Normalizer\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class NormalizerInitializer implements
    InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof NormalizerAwareInterface) {
            if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            /** @var \Application\Core\Normalizer\JMSSerializerBasedNormalizer $normalizer */
            $normalizer = $serviceLocator->get(
                'Application\Core\Normalizer\JMSSerializerBasedNormalizer'
            );

            $instance->setNormalizer($normalizer);
        }
    }
}
