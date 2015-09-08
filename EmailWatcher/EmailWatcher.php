<?php

namespace K3roulas\EmailWatcher;

use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistFile;
use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistInterface;

/**
 * Class EmailWatcher
 *
 * @package K3roulas\EmailWatcher
 */
class EmailWatcher
{

    /** @var  string */
    private $server;
    /** @var  integer */
    private $port;
    /** @var  string */
    private $email;
    /** @var  string*/
    private $password;
    /** @var string */
    private $filename;
    /** @var  NewEmailWatcherInterface */
    private $newEmailWatcher;
    /** @var  integer */
    private $nbMaxMessage;

    /** @var  boolean */
    private $initCalled;

    /** @var LastUidPersistInterface  */
    private $lastUidPersist;
    /** @var Fetcher */
    private $fetcher;
    /** @var  EmailTrigger */
    private $emailTrigger;

    /**
     *
     */
    public function __construct()
    {
        $this->fetcher = null;
        $this->newEmailWatcher = null;
    }

    /**
     * @param string $email
     *
     * @return EmailWatcher
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return EmailWatcher
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return EmailWatcher
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $server
     *
     * @return EmailWatcher
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @param string $filename
     *
     * @return EmailWatcher
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param NewEmailWatcherInterface $newEmailWatcher
     *
     * @return EmailWatcher
     */
    public function setNewEmailWatcher($newEmailWatcher)
    {
        $this->newEmailWatcher = $newEmailWatcher;

        return $this;
    }

    /**
     * @param int $nbMaxMessage
     *
     * @return EmailWatcher
     */
    public function setNbMaxMessage($nbMaxMessage)
    {
        $this->nbMaxMessage = $nbMaxMessage;

        return $this;
    }


    /**
     * @throws \Exception
     */
    public function init()
    {
        if (null === $this->server) {
            throw new \Exception('server is null, you must init a valid server by calling setServer');
        }
        if (null === $this->port) {
            throw new \Exception('port is null, you must init a valid port by calling setPort');
        }
        if (null === $this->email) {
            throw new \Exception('email is null, you must init a valid email by calling setEmail');
        }
        if (null === $this->password) {
            throw new \Exception('password is null, you must init a valid password by calling setPassword');
        }
        if (null === $this->newEmailWatcher) {
            throw new \Exception('newEmailWatcher is null, you must call setNewEmailWatcher');
        }

        if (false === ($this->newEmailWatcher instanceof NewEmailWatcherInterface)) {
            throw new \Exception('newEmailWatcher, must implement K3roulas\EmailWatcher\NewEmailWatcherInterface');
        }

        $this->initCalled = true;

        $this->fetcher = new Fetcher(
            $this->server,
            $this->port,
            $this->email,
            $this->password
        );

        $this->lastUidPersist = new LastUidPersistFile($this->filename);

        $this->emailTrigger = new EmailTrigger(
            $this->fetcher,
            $this->newEmailWatcher,
            $this->lastUidPersist
        );
    }

    /**
     * @throws \Exception
     */
    public function process()
    {
        if (false === $this->initCalled) {
            throw new \Exception('You must call the method init()');
        }

        $this->emailTrigger->process();
    }

} 