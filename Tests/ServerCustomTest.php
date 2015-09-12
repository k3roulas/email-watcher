<?php

namespace K3roulas\EmailWatcher\Test;


use Fetch\Message;
use Fetch\Server;

class ServerCustomTest extends \PHPUnit_Framework_TestCase
{

    private $uids;
    private $messages;


    public function testServerCustomIsCorrectlyCreated()
    {
        // TODO ?


    }

    public function testGetLastUid()
    {
//        $server = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
//            ->disableOriginalConstructor()
//            ->setMethods(array('numMessages', 'getImapStream', 'decodeUid'))
//            ->getMock();
//        $imapStream = 99;
//        $numMessages = 5;
//        $uid = 7;
//
//
//        $server->expects($this->once())
//            ->method('numMessages')
//            ->will($this->returnValue($numMessages));
//
//        $server->expects($this->once())
//            ->method('getImapStream')
//            ->will($this->returnValue($imapStream));
//
//        $server->expects($this->once())
//            ->method('decodeUid')
//            ->with($imapStream, $numMessages)
//            ->will($this->returnValue($uid));
//
//        $result = $server->getLastUid();
//        $this->assertTrue($result == $uid, 'Last uid incorrect');

    }

    private function getServer()
    {

    }

    private function initForGetMessageUntil()
    {
        $this->messages = array();
        foreach ($this->uids as $uid) {

            $message = $this->getMockBuilder('Fetch\Message')
                ->disableOriginalConstructor()
                ->getMock();

            $message->expects($this->any())
                ->method('getUid')
                ->will($this->returnValue($uid));

            $this->messages[] = $message;
        }

        $numMessages = count($this->uids);

        $imapStream = 99;

        $server = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array('numMessages', 'getImapStream', 'decodeUid', 'createMessage'))
            ->getMock();

        $server->expects($this->any())
            ->method('decodeUid')
            ->willReturnCallback(array($this, 'getMessageForTestGetMessageUntil'));

        $server->expects($this->any())
            ->method('createMessage')
            ->willReturnCallback(array($this, 'createMessageForTestGetMessageUntil'));

        $server->expects($this->any())
            ->method('getImapStream')
            ->will($this->returnValue($imapStream));

        $server->expects($this->any())
            ->method('numMessages')
            ->will($this->returnValue(count($this->uids)));

        return $server;

    }


    public function testGetUidUntilNormal()
    {
        $limit = 10;
        $untilUid =103;

        $this->uids = array(101,102,103,104,105,106);

        $server = $this->initForGetMessageUntil($this->uids);

        $messages = $server->getMessageUntil($untilUid, $limit);

        $this->checkResults(
            $messages,
            array(104,105,106)
        );


    }

    public function testGetUidUntilDiscontinuous()
    {
        $limit = 10;
        $untilUid =103;

        $this->uids = array(101,102,104,105,106);

        $server = $this->initForGetMessageUntil($this->uids);

        $messages = $server->getMessageUntil($untilUid, $limit);

        $this->checkResults($messages, array(101,102,104,105,106));

    }

    public function testGetUidUntilOnLimit()
    {
        $limit = 6;
        $untilUid =103;

        $this->uids = array(101,102,104,105,106);

        $server = $this->initForGetMessageUntil($this->uids);

        $messages = $server->getMessageUntil($untilUid, $limit);

        $this->checkResults($messages, array(101,102,104,105,106));

    }

    public function testGetUidUntilBeforeLimit()
    {
        $limit = 3;
        $untilUid =103;

        $this->uids = array(101,102,104,105,106);

        $server = $this->initForGetMessageUntil($this->uids);

        $messages = $server->getMessageUntil($untilUid, $limit);

        $this->checkResults($messages, array(104,105,106));

    }

    public function testGetUidUntilEmptyUid()
    {
        $limit = 10;
        $untilUid =103;

        $this->uids = array();

        $server = $this->initForGetMessageUntil($this->uids);

        $messages = $server->getMessageUntil($untilUid, $limit);

        $this->checkResults($messages, array());

    }

    private function checkResults($messages, array $expectedUid)
    {

        $expectedUidPosition = count($expectedUid);

        foreach ($messages as $message) {

            $expectedUidPosition--;
            $this->assertTrue($message->getUid() ==  $expectedUid[$expectedUidPosition], 'The order is in an incorrect position ' . $expectedUidPosition);

        }

        $this->assertTrue(count($expectedUid) == count($messages), "wrong number of messages : " . count($messages) . " instead of " . count($expectedUid));


    }

    public function getMessageForTestGetMessageUntil()
    {
        $position = func_get_arg(1);

        return $this->uids[$position-1];
    }

    public function createMessageForTestGetMessageUntil()
    {
        $uid = func_get_arg(0);

        $position = array_search($uid, $this->uids);

        return $this->messages[$position];
    }


} 