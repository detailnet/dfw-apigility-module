<?php

namespace Application\Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LogLevel;

use Detail\Log\Service\LoggerAwareTrait;
use Detail\Mail\Service\MailerAwareInterface;
use Detail\Mail\Service\MailerAwareTrait;

use Detail\Gaufrette\Service\FilesystemServiceInterface;
use Detail\Gaufrette\Service\FilesystemServiceAwareInterface;
use Detail\Gaufrette\Service\FilesystemServiceAwareTrait;

use Gaufrette\File;

use Application\User\Service\UserService;
use Application\User\Service\UserServiceAwareInterface;
use Application\User\Service\UserServiceAwareTrait;

use RuntimeException;

class TestController extends AbstractActionController implements
    LoggerAwareInterface,
    MailerAwareInterface,
    UserServiceAwareInterface,
    FilesystemServiceAwareInterface
{
    use LoggerAwareTrait;
    use MailerAwareTrait;
    use UserServiceAwareTrait;
    use FilesystemServiceAwareTrait;

    public function __construct(UserService $userService)
    {
        $this->setUserService($userService);
    }

    /**
     * Demos the following:
     * - Logging
     * - Session
     * - Mail
     *
     * Note that you need to be logged in with an "admin" role to call this action.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $session = new Container('default');

        if (!isset($session->pageCounter)) {
            $session->pageCounter = 0;
        }

        $session->pageCounter += 1;

        $uploads = $this->getFilesystemService()->get('uploads');

        $fileName = 'test.txt';
        $fileContents = 'Hello ' . $session->pageCounter;

        // Files can be created like this...
        $file = new File($fileName, $uploads);
        $file->setContent($fileContents); // Will actually write the file to the filesystem

        // ...or this.
        $uploads->createFile($fileName)->setContent($fileContents);

        $this->log(
            sprintf(
                'The page "/application/test" was visited %d time(s)', $session->pageCounter
            ),
            LogLevel::INFO
        );

        $data = array(
            'pageCounter' => $session->pageCounter,
        );

        $this->sendMail(
            'test/session',
            array(
                'subject' => 'Zend Framework 2 Test',
                'to' => 'development@detailag.ch'
            ),
            $data
        );

        return new ViewModel($data);
    }

    /**
     * @throws RuntimeException
     * @return FilesystemServiceInterface
     */
    protected function getFilesystemService()
    {
        if ($this->filesystemService === null) {
            throw new RuntimeException('No filesystem service provided');
        }

        return $this->filesystemService;
    }
}
