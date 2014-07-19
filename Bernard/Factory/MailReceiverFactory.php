<?php

namespace Application\Core\Bernard\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\Core\Bernard\Receiver\MailReceiver;

class MailReceiverFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\Mail\Service\MailerInterface $mailer */
        $mailer = $serviceLocator->get('direct_mailer'); /** @todo Introduce "receivers" config which references the mailer to use */

        /** @var \Detail\Mail\Driver\Bernard\BernardService $bernardService */
        $bernardService = $serviceLocator->get('Detail\Mail\Driver\Bernard\BernardService');

        $service = new MailReceiver($mailer, $bernardService);

        return $service;
    }
}
