<?php

namespace K3roulas\EmailWatcher;


use Fetch\Message;

/**
 * Interface NewEmailWatcherInterface
 *
 * @package TTPS\EmailBloggerBundle\EmailBlogger\EmailTrigger
 */
interface NewEmailWatcherInterface {

    /**
     * @param Message $message
     *
     * @return mixed
     */
    public function newEmail(Message $message);

} 