<?php

namespace K3roulas\EmailWatcher;

use Fetch\Message;
use Fetch\Server;

/**
 * Class Fetcher
 *
 * @package TTPS\EmailBloggerBundle\EmailBlogger
 */
class Fetcher
{

    /** @var  Server */
    private $server;

    /**
     * @param string $server
     * @param int    $port
     * @param string $email
     * @param string $password
     */
    public function __construct($server, $port, $email, $password)
    {
        $this->server = $this->createServerCustom($server, $port);
        $this->server->setAuthentication($email, $password);
    }

    /**
     * @param string  $server
     * @param integer $port
     *
     * @return ServerCustom
     */
    protected function createServerCustom($server, $port)
    {
        return new ServerCustom($server, $port);
    }

    /**
     * @return int|null
     */
    public function getLastUid()
    {
        return $this->server->getLastUid();
    }

    /**
     * @param int $uid
     * @param int $limit
     *
     * @return array
     */
    public function getMessageUntil($uid, $limit)
    {
        return $this->server->getMessageUntil($uid, $limit);
    }

} 