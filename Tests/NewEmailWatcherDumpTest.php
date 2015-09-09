<?php

namespace K3roulas\EmailWatcher\Test;


use Fetch\Message;
use K3roulas\EmailWatcher\NewEmailWatcherDump;

class NewEmailWatcherDumpTest extends  \PHPUnit_Framework_TestCase
{

    public function testMessageCorrectlyDumped()
    {
        $message = $this->getMockBuilder('Fetch\Message')
            ->disableOriginalConstructor()
            ->getMock();

        $message->expects($this->once())
            ->method('getUid');
        $message->expects($this->once())
            ->method('getSubject');
        $message->expects($this->once())
            ->method('getDate');

        $dump = new NewEmailWatcherDump();
        $dump->newEmail($message);

    }


} 