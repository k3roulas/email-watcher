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
class EmailTriggerTest extends \PHPUnit_Framework_TestCase
{


    public function testNewEmailAreProcessed()
    {
        $lastPersistedUid = 21;
        $lastFetcherUid = 23;
        $messagesUid = array(23, 24);

        $messages = array();

        foreach ($messagesUid as $uid) {
            $message = $this->getMockBuilder('Fetch\Message')
                ->disableOriginalConstructor()
                ->setMethods(array())
                ->getMock();
            $messages[] = $message;
        }


        $lastUidPersistObject = $this->getMock(
            'K3roulas\EmailWatcher\LastUidPersist\LastUidPersistInterface',
            array('getLastUid', 'setLastUid')
        );

        $lastUidPersistObject->expects($this->once())
            ->method('getLastUid')
            ->will($this->returnValue($lastPersistedUid));

        $lastUidPersistObject->expects($this->once())
            ->method('setLastUid');

        $fetcher = $this->getMockBuilder('K3roulas\EmailWatcher\Fetcher')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $fetcher->expects($this->once())
            ->method('getMessageUntil')
            ->will($this->returnValue($messages));

        $fetcher->expects($this->once())
            ->method('getLastUid')
            ->will($this->returnValue($lastFetcherUid));

        $fetcher->expects($this->once())
            ->method('getMessageUntil')
            ->will($this->returnValue($messages));

        $watcher = $this->getMock('K3roulas\EmailWatcher\NewEmailWatcherInterface');

        $i = 0;
        foreach ($messages as $message) {
            $watcher->expects($this->at($i++))
                ->method('newEmail')
                ->with($this->identicalTo($message));
        }
        $watcher->expects($this->exactly(count($messages)))
            ->method('newEmail');

        $emailTrigger = new EmailTrigger($fetcher, $watcher, $lastUidPersistObject, 12);

        $emailTrigger->process();

    }

    public function testNoNewEmailAreProcessed()
    {
        $lastPersistedUid = 21;
        $lastFetcherUid = 21;

        $lastUidPersistObject = $this->getMock(
            'K3roulas\EmailWatcher\LastUidPersist\LastUidPersistInterface',
            array('getLastUid', 'setLastUid')
        );

        $lastUidPersistObject->expects($this->once())
            ->method('getLastUid')
            ->will($this->returnValue($lastPersistedUid));

        $fetcher = $this->getMockBuilder('K3roulas\EmailWatcher\Fetcher')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $fetcher->expects($this->never())
            ->method('getMessageUntil');

        $lastUidPersistObject->expects($this->never())
            ->method('setLastUid');

        $fetcher->expects($this->once())
            ->method('getLastUid')
            ->will($this->returnValue($lastFetcherUid));

        $watcher = $this->getMock('K3roulas\EmailWatcher\NewEmailWatcherInterface');

        $emailTrigger = new EmailTrigger($fetcher, $watcher, $lastUidPersistObject, 12);

        $emailTrigger->process();

    }


} 