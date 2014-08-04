<?php

namespace Application\Core\Bernard\Receiver;

use Detail\Bernard\Receiver\AbstractReceiver;
use Detail\Mail\Service\MailerInterface as Mailer;
use Detail\Mail\Driver\Bernard\BernardService;

use Bernard\Message as BernardMessage;

use Psr\Log\LogLevel;

class MailReceiver extends AbstractReceiver
{
    /**
     * @var Mailer
     */
    protected $mailer = null;

    /**
     * @var BernardService
     */
    protected $bernardService = null;

    public function __construct(Mailer $mailer, BernardService $bernardService)
    {
        $this->mailer = $mailer;
        $this->bernardService = $bernardService;
    }

    public function receive(BernardMessage $message)
    {
        try {
            $mailMessage = $this->getBernardService()->decodeMessage($message);

            $this->getMailer()->send($mailMessage);
        } catch (\Exception $e) {
            /** @todo Handle known exception for better readability... */
            $this->log($e, LogLevel::CRITICAL);
            throw $e; // So that the message in not de-queued.

            /** @todo It seems, an exception is sometimes propagated to the CLI, sometimes not... we should investigate this. */
        }
    }

    protected function getMailer()
    {
        return $this->mailer;
    }

    protected function getBernardService()
    {
        return $this->bernardService;
    }
}
