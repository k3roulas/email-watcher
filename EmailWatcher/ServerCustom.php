<?php

namespace K3roulas\EmailWatcher;


use Fetch\Message;
use Fetch\Server;

/**
 * Class ServerCustom
 *
 * @package TTPS\EmailBloggerBundle\EmailBlogger
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
            $uid = imap_uid($stream, $numMessages);
        }

        return $uid;
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
            $newUid        = imap_uid($stream, $i);
            if ($newUid === $uid) {
                break;
            }
            $messages[] = new Message($newUid, $this);
        }

        return $messages;
    }

} 