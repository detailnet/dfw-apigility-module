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

        /** @var \Detail\Mail\Driver\Bernard\BernardDriver $bernardDriver */
        $bernardDriver = $serviceLocator->get('Detail\Mail\Driver\Bernard\BernardDriver');

        $service = new MailReceiver($mailer, $bernardDriver->getMessenger());

        return $service;
    }
}
