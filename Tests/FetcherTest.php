<?php

namespace K3roulas\EmailWatcher\Test;


use K3roulas\EmailWatcher\Fetcher;
use K3roulas\EmailWatcher\ServerCustom;

class FetcherTest extends \PHPUnit_Framework_TestCase
{

    public function testCreation()
    {
        $server = 'server';
        $port = 7666;
        $email = 'email@email.com';
        $password = 'verySecret';

        $serverCustom = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array('setAuthentication'))
            ->getMock();

        $serverCustom->expects($this->once())
            ->method('setAuthentication')
            ->with($email, $password, true)
            ;

        $fetcher = $this->getMockBuilder('K3roulas\EmailWatcher\Fetcher')
            ->disableOriginalConstructor()
            ->setMethods(array('createServerCustom'))
            ->getMock()
            ;

        $fetcher->expects($this->once())
            ->method('createServerCustom')
            ->with($server, $port)
            ->will($this->returnValue($serverCustom));

        $fetcher->__construct($server, $port, $email, $password);

    }

    public function testGetLastUid()
    {
        $server = 'server';
        $port = 7666;
        $email = 'email@email.com';
        $password = 'verySecret';

        $serverCustom = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array('getLastUid', 'setAuthentication'))
            ->getMock();

        $serverCustom->expects($this->once())
            ->method('getLastUid')
            ;

        $fetcher = $this->getMockBuilder('K3roulas\EmailWatcher\Fetcher')
            ->disableOriginalConstructor()
            ->setMethods(array('createServerCustom'))
            ->getMock()
            ;

        $fetcher->expects($this->once())
            ->method('createServerCustom')
            ->with($server, $port)
            ->will($this->returnValue($serverCustom));

        $fetcher->__construct($server, $port, $email, $password);
        $fetcher->getLastUid();

    }

    public function testGetMessageUntil()
    {
        $server = 'server';
        $port = 7666;
        $email = 'email@email.com';
        $password = 'verySecret';
        $uid = 156;
        $limit = 2056;

        $serverCustom = $this->getMockBuilder('K3roulas\EmailWatcher\ServerCustom')
            ->disableOriginalConstructor()
            ->setMethods(array('getMessageUntil', 'setAuthentication'))
            ->getMock();

        $serverCustom->expects($this->once())
            ->method('getMessageUntil')
            ->with($uid, $limit)
            ;

        $fetcher = $this->getMockBuilder('K3roulas\EmailWatcher\Fetcher')
            ->disableOriginalConstructor()
            ->setMethods(array('createServerCustom'))
            ->getMock()
            ;

        $fetcher->expects($this->once())
            ->method('createServerCustom')
            ->with($server, $port)
            ->will($this->returnValue($serverCustom));

        $fetcher->__construct($server, $port, $email, $password);
        $fetcher->getMessageUntil($uid, $limit);

    }

}