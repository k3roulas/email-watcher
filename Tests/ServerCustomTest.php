<?php

namespace K3roulas\EmailWatcher\Test;


use Fetch\Message;
use Fetch\Server;

class ServerCustomTest extends \PHPUnit_Framework_TestCase
{

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


    public function testGetMessageUntil()
    {
        $limit = 10;
        $untilUid =103;

        $this->messages = array(101,102,103,104,105,106);
        $numMessages = count($this->messages);

        $imapStream = 99;

        $server = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array('numMessages', 'getImapStream', 'decodeUid'))
            ->getMock();

        $server->expects($this->any())
            ->method('decodeUid')
            ->willReturnCallback(array($this, 'getMessageForTestGetMessageUntil'));

        $server->expects($this->any())
            ->method('createMessage')
            ->willReturnCallback(array($this, 'createMessageForTestGetMessageUntil'));

        $server->expects($this->once())
            ->method('getImapStream')
            ->will($this->returnValue($imapStream));

        $server->expects($this->any())
            ->method('numMessages')
            ->will($this->returnValue(count($this->messages)));

        $server->getMessageUntil($untilUid, $limit);

    }

    public function getMessageForTestGetMessageUntil()
    {
        $position = func_get_arg(1);

        return $this->messages[$position-1];
    }

    public function createMessageForTestGetMessageUntil()
    {
        $position = func_get_arg(1);

        return $this->messages[$position-1];
    }


} 