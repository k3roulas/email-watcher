<?php

namespace K3roulas\EmailWatcher;

use Fetch\Message;

/**
 * Interface NewEmailWatcherInterface
 *
 * @package TTPS\EmailBloggerBundle\EmailBlogger\EmailTrigger
 */
class NewEmailWatcherDump implements NewEmailWatcherInterface
{

    /**
     * @param Message $message
     *
     * @return mixed
     */
    public function newEmail(Message $message)
    {
        $date = new \DateTime();
        $date->setTimeStamp($message->getDate());

        echo 'Subject : ' . $message->getSubject() . "\n";
        echo 'Uid     : ' . $message->getUid() . "\n";
        echo 'Date    : ' . $date->format('Y/m/d H:i:s') . "\n";
    }

} 