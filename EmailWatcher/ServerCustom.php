<?php

namespace K3roulas\EmailWatcher;


use Fetch\Message;
use Fetch\Server;

/**
 * Class ServerCustom
 */
class ServerCustom
{

    /**
     * @var \Fetch\Server
     */
    private $server;

    /**
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * @return int|null
     */
    public function getLastUid()
    {
        $uid = null;

        $numMessages = $this->server->numMessages();
        $stream = $this->server->getImapStream();
        if (0 != $numMessages) {
            $uid = $this->getMessage($stream, $numMessages);
        }

        return $uid;
    }

    /**
     * @param integer $stream
     * @param integer $messageNumber
     *
     * @return int
     */
    protected function getMessage($stream, $messageNumber)
    {
        return imap_uid($stream, $messageNumber);
    }

    /**
     * @param integer $uid
     *
     * @return Message
     */
    protected function createMessage($uid)
    {
        return new Message($uid, $this->server);
    }

    /**
     * Get message : max number of message and stop if find uid message
     *
     * @param int $uid
     * @param int $limit
     *
     * @return array
     */
    public function getMessageUntil($uid, $limit=10)
    {
        $messages = array();

        $numMessages = $this->server->numMessages();

        if ($numMessages > $limit) {
            $numLimit = $numMessages - $limit;
        } else {
            $numLimit = 0;
        }

        $stream = $this->server->getImapStream();

        for ($i = $numMessages; $i > $numLimit; $i--) {
            $newUid        = $this->getMessage($stream, $i);
            if ($newUid === $uid) {
                break;
            }
            $messages[] = $this->createMessage($newUid);
        }

        return $messages;
    }

} 