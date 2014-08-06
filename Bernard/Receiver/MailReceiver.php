<?php

namespace Application\Core\Bernard\Receiver;

use Detail\Bernard\Message\Messenger;
use Detail\Bernard\Receiver\AbstractReceiver;
use Detail\Mail\Service\MailerInterface as Mailer;

use Bernard\Message as BernardMessage;

use Psr\Log\LogLevel;

class MailReceiver extends AbstractReceiver
{
    /**
     * @var Mailer
     */
    protected $mailer = null;

    /**
     * @var Messenger
     */
    protected $messenger = null;

    /**
     * @param Mailer $mailer
     * @param Messenger $messenger
     */
    public function __construct(Mailer $mailer, Messenger $messenger)
    {
        $this->mailer = $mailer;
        $this->messenger = $messenger;
    }

    /**
     * @param BernardMessage $message
     * @throws \Exception
     */
    public function receive(BernardMessage $message)
    {
        try {
            $mailMessage = $this->getMessenger()->decodeMessage($message);

            $this->getMailer()->send($mailMessage);
        } catch (\Exception $e) {
            /** @todo Handle known exception for better readability... */
            $this->log($e, LogLevel::CRITICAL);
            throw $e; // So that the message in not de-queued.

            /** @todo It seems, an exception is sometimes propagated to the CLI, sometimes not... we should investigate this. */
        }
    }

    /**
     * @return Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @return Messenger
     */
    protected function getMessenger()
    {
        return $this->messenger;
    }
}
