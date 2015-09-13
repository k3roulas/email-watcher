<?php

namespace K3roulas\EmailWatcher;

use Fetch\Server;
use K3roulas\EmailWatcher\Exception\ParameterException;
use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistFile;
use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistInterface;

/**
 * Class EmailWatcher
 *
 * @package K3roulas\EmailWatcher
 */
class Watcher
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
    /** @var  LastUidPersistInterface */
    private $lastUidPersist;
    /** @var  integer */
    private $nbMaxMessage;
    /** @var string */
    private $protocol;

    /** @var  boolean */
    private $initCalled;

    /** @var Processor */
    private $processor;

    /** @var Server */
    private $fetchServer;

    public function __construct()
    {
        $this->initCalled = false;
    }

    /**
     * @param string $email
     *
     * @return Watcher
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return Watcher
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return Watcher
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $server
     *
     * @return Watcher
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @param string $filename
     *
     * @return Watcher
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param string $protocol
     *
     * @return Watcher
     */
    public function setProtocol($protocol)
    {
        $allowedProtocol = array('imap', 'pop3');
        if (false === array_search($protocol, $allowedProtocol)) {
            throw new ParameterException('Unknown protocol');
        }

        $this->protocol = $protocol;

        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param NewEmailWatcherInterface $newEmailWatcher
     *
     * @return Watcher
     */
    public function setNewEmailWatcher(NewEmailWatcherInterface $newEmailWatcher)
    {
        $this->newEmailWatcher = $newEmailWatcher;

        return $this;
    }

    /**
     * @param int $nbMaxMessage
     *
     * @return Watcher
     */
    public function setNbMaxMessage($nbMaxMessage)
    {
        $this->nbMaxMessage = $nbMaxMessage;

        return $this;
    }

    /**
     * @param \Fetch\Server $fetchServer
     *
     * @return Watcher
     */
    public function setFetchServer(Server $fetchServer)
    {
        $this->fetchServer = $fetchServer;

        return $this;
    }

    /**
     * @param LastUidPersistInterface $lastUidPersist
     *
     * @return Watcher
     */
    public function setLastUidPersist(LastUidPersistInterface $lastUidPersist)
    {
        $this->lastUidPersist = $lastUidPersist;

        return $this;
    }

    private function checkConfigurationWithFetchServer()
    {
        if (null === $this->server) {
            throw new ParameterException('server is null, you must init a valid server by calling setServer');
        }
        if (null === $this->port) {
            throw new ParameterException('port is null, you must init a valid port by calling setPort');
        }
        if (null === $this->email) {
            throw new ParameterException('email is null, you must init a valid email by calling setEmail');
        }
        if (null === $this->password) {
            throw new ParameterException('password is null, you must init a valid password by calling setPassword');
        }
        if (null === $this->protocol) {
            throw new ParameterException('protocol is null, you must init a valid protocol by calling setProtocol');
        }
    }

    private function checkConfigurationWithoutFetchServer()
    {
        if (null !== $this->server) {
            throw new ParameterException('server has to be null when you call setFetchServer');
        }
        if (null !== $this->port) {
            throw new ParameterException('port has to be null when you call setFetchServer');
        }
        if (null !== $this->email) {
            throw new ParameterException('email has to be null when you call setFetchServer');
        }
        if (null !== $this->password) {
            throw new ParameterException('password has to be null when you call setFetchServer');
        }
        if (null !== $this->protocol) {
            throw new ParameterException('protocol has to be null when you call setFetchServer');
        }

    }

    private function checkConfiguration()
    {
        if (null === $this->fetchServer) {
            $this->checkConfigurationWithFetchServer();
        }

        if (null !== $this->fetchServer) {
            $this->checkConfigurationWithoutFetchServer();
        }

        if (null === $this->newEmailWatcher) {
            throw new ParameterException('newEmailWatcher is null, you must call setNewEmailWatcher');
        }

        if (null === $this->lastUidPersist && null === $this->filename) {
            throw new ParameterException('a filename or a lastUidPersist object has to be provided');
        }

        if (null !== $this->lastUidPersist && null !== $this->filename) {
            throw new ParameterException('make a choice between using a filename or the a lastuidPersist object');
        }


    }

    protected function createServer($server, $port)
    {
        return new Server($server, $port);
    }


    /**
     * @throws ParameterException
     */
    public function init()
    {
        $this->checkConfiguration();

        $this->initCalled = true;

        if ($this->fetchServer === null) {
            $this->fetchServer = $this->createServer($this->server, $this->port);
            $this->fetchServer->setAuthentication($this->email, $this->password);
        }

        $serverCustom = new ServerCustom($this->fetchServer);

        if ($this->filename) {
            $this->lastUidPersist = new LastUidPersistFile($this->filename);
        }

        $this->processor = new Processor(
            $serverCustom,
            $this->newEmailWatcher,
            $this->lastUidPersist
        );
    }

    /**
     * @throws ParameterException
     */
    public function process()
    {
        if (false === $this->initCalled) {
            throw new \Exception('You must call the method init()');
        }

        $this->processor->process();
    }

} 