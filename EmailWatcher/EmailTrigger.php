<?php

namespace K3roulas\EmailWatcher;

use Fetch\Attachment;
use Fetch\Message;
use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistInterface;

/**
 * Class EmailTrigger
 *
 * @package K3roulas\EmailWatcher
 */
class EmailTrigger
{

    /** @var  Fetcher */
    private $fetcher;

    /** @var  NewEmailWatcherInterface  */
    private $watcher;

    /** @var integer */
    private $maxNewEmails;

    /** @var LastUidPersistInterface  */
    private $lastUidPersist;

    /**
     * @param Fetcher                  $emailFetcher
     * @param NewEmailWatcherInterface $watcher
     * @param LastUidPersistInterface  $lastUidPersist
     * @param integer                  $maxNewEmails
     */
    public function __construct(Fetcher $emailFetcher,NewEmailWatcherInterface $watcher, LastUidPersistInterface $lastUidPersist, $maxNewEmails = 15)
    {
        $this->fetcher = $emailFetcher;
        $this->watcher = $watcher;
        $this->lastUidPersist = $lastUidPersist;
        $this->maxNewEmails = $maxNewEmails;
    }

    /**
     * Check new emails
     */
    public function process()
    {
        $lastStoredUid = $this->lastUidPersist->getLastUid();
        $lastRemoteId = $this->fetcher->getLastUid();

        // Test if new messages arrived
        if ($lastStoredUid != $lastRemoteId) {
            /** @var Message[] $messages */
            $messages = $this->fetcher->getMessageUntil($lastStoredUid, $this->maxNewEmails);

            foreach ($messages as $message) {
                $this->watcher->newEmail($message);
            }

        }

        $this->lastUidPersist->setLastUid($lastRemoteId);
    }


} 