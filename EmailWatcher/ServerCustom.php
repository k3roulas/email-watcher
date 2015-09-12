<?php

namespace K3roulas\EmailWatcher;


use Fetch\Message;
use Fetch\Server;

/**
 * Class ServerCustom
 */
class ServerCustom extends Server
{
    /**
     * @param string $serverPath
     * @param int    $port
     * @param string $service
     */
    public function __construct($serverPath, $port = 143, $service = 'imap')
    {
        parent::__construct($serverPath, $port, $service);
    }


    /**
     * @return int|null
     */
    public function getLastUid()
    {
        $uid = null;

        $numMessages = $this->numMessages();
        $stream = $this->getImapStream();
        if (0 != $numMessages) {
            $uid = $this->decodeUid($stream, $numMessages);
        }

        return $uid;
    }

    protected function decodeUid($stream, $messageNumber)
    {
        return imap_uid($stream, $messageNumber);
    }

    protected function createMessage($uid)
    {
        return new Message($uid, $this);
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

        $numMessages = $this->numMessages();

        if ($numMessages > $limit) {
            $numLimit = $numMessages - $limit;
        } else {
            $numLimit = 0;
        }

        $stream = $this->getImapStream();

        for ($i = $numMessages; $i > $numLimit; $i--) {
            $newUid        = $this->decodeUid($stream, $i);
            if ($newUid === $uid) {
                break;
            }
            $messages[] = $this->createMessage($newUid);
        }

        return $messages;
    }

} 