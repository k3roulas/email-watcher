<?php

namespace K3roulas\EmailWatcher;

use Fetch\Attachment;
use Fetch\Message;
use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistInterface;

/**
 * Class Processor
 *
 * @package K3roulas\Processor
 */
class Processor
{

    /** @var  ServerCustom */
    private $serverCustom;

    /** @var  NewEmailWatcherInterface  */
    private $watcher;

    /** @var integer */
    private $maxNewEmails;

    /** @var LastUidPersistInterface  */
    private $lastUidPersist;

    /**
     * @param ServerCustom             $serverCustom
     * @param NewEmailWatcherInterface $watcher
     * @param LastUidPersistInterface  $lastUidPersist
     * @param integer                  $maxNewEmails
     */
    public function __construct(ServerCustom $serverCustom, NewEmailWatcherInterface $watcher, LastUidPersistInterface $lastUidPersist, $maxNewEmails = 15)
    {
        $this->serverCustom = $serverCustom;
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
        $lastRemoteId = $this->serverCustom->getLastUid();

        // Test if new messages arrived
        if ($lastStoredUid != $lastRemoteId) {
            /** @var Message[] $messages */
            $messages = $this->serverCustom->getMessageUntil($lastStoredUid, $this->maxNewEmails);

            foreach ($messages as $message) {
                $this->watcher->newEmail($message);
            }

            $this->lastUidPersist->setLastUid($lastRemoteId);
        }

    }


} 