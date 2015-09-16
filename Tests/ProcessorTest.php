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
class ProcessorTest extends \PHPUnit_Framework_TestCase
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

        $serverCustom = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $serverCustom->expects($this->once())
            ->method('getMessageUntil')
            ->will($this->returnValue($messages));

        $serverCustom->expects($this->once())
            ->method('getLastUid')
            ->will($this->returnValue($lastFetcherUid));

        $serverCustom->expects($this->once())
            ->method('getMessageUntil')
            ->will($this->returnValue($messages));

        $newEmailWatcher = $this->getMock('K3roulas\EmailWatcher\NewEmailWatcherInterface');

        $i = 0;
        foreach ($messages as $message) {
            $newEmailWatcher->expects($this->at($i++))
                ->method('newEmail')
                ->with($this->identicalTo($message));
        }
        $newEmailWatcher->expects($this->exactly(count($messages)))
            ->method('newEmail');

        $processor = new Processor($serverCustom, $newEmailWatcher, $lastUidPersistObject, 12);

        $processor->process();

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

        $serverCustom = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $serverCustom->expects($this->never())
            ->method('getMessageUntil');

        $lastUidPersistObject->expects($this->never())
            ->method('setLastUid');

        $serverCustom->expects($this->once())
            ->method('getLastUid')
            ->will($this->returnValue($lastFetcherUid));

        $newEmailWatcher = $this->getMock('K3roulas\EmailWatcher\NewEmailWatcherInterface');

        $processor = new Processor($serverCustom, $newEmailWatcher, $lastUidPersistObject, 12);

        $processor->process();

    }


} 