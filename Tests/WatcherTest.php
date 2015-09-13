<?php

namespace K3roulas\EmailWatcher\Test;


use K3roulas\EmailWatcher\NewEmailWatcherDump;
use K3roulas\EmailWatcher\Watcher;

class WatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testNominalParameter()
    {

        $watcher = $this->getMockBuilder('K3roulas\EmailWatcher\Watcher')
            ->setMethods(array('createServer'))
            ->getMock();

        $fetchServer = $this->getMockBuilder('Fetch\Server')
            ->disableOriginalConstructor()
            ->setMethods(array('setAuthentication'))
            ->getMock();

        $fetchServer->expects($this->once())
            ->method('setAuthentication');


        $watcher->expects($this->once())
            ->method('createServer')
            ->will($this->returnValue($fetchServer));

        $newEmail = new NewEmailWatcherDump();

        $watcher->setServer('server')
            ->setPort('port')
            ->setEmail('email')
            ->setProtocol('imap')
            ->setPassword('password')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }

    public function testParameterWithoutPort()
    {
        $newEmail = new NewEmailWatcherDump();

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $watcher = new Watcher();

        $watcher->setServer('server')
            ->setEmail('email')
            ->setProtocol('imap')
            ->setPassword('password')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }


    public function testParameterWithoutEmail()
    {
        $newEmail = new NewEmailWatcherDump();

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $watcher = new Watcher();

        $watcher->setServer('server')
            ->setPort('port')
            ->setProtocol('imap')
            ->setPassword('password')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }

    public function testParameterWithoutProtocol()
    {
        $newEmail = new NewEmailWatcherDump();

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $watcher = new Watcher();

        $watcher->setServer('server')
            ->setPort('port')
            ->setEmail('email')
            ->setPassword('password')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }

    public function testParameterWithoutPassword()
    {
        $newEmail = new NewEmailWatcherDump();

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $watcher = new Watcher();

        $watcher->setServer('server')
            ->setPort('port')
            ->setEmail('email')
            ->setProtocol('imap')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }

    public function testParameterWithoutFilename()
    {
        $newEmail = new NewEmailWatcherDump();

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $watcher = new Watcher();

        $watcher->setServer('server')
            ->setPort('port')
            ->setEmail('email')
            ->setProtocol('imap')
            ->setPassword('password')
            ->setFilename('filename')
            ->init();
    }

    public function testParameterWithoutFetchServer()
    {
        $newEmail = new NewEmailWatcherDump();

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $watcher = new Watcher();

        $watcher
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }

    public function testParameterLastUid()
    {

        $this->setExpectedException('K3roulas\EmailWatcher\Exception\ParameterException');

        $fetchServer = $this->getMockBuilder('Fetch\Server')
            ->disableOriginalConstructor()
            ->setMethods(array('setAuthentication'))
            ->getMock();


        $watcher = new Watcher();

        $watcher
            ->setFetchServer($fetchServer)
            ->init();
    }



    public function testParameterWithFetchServer()
    {

        $watcher = $this->getMockBuilder('K3roulas\EmailWatcher\Watcher')
            ->setMethods(null)
            ->getMock();

        $fetchServer = $this->getMockBuilder('Fetch\Server')
            ->disableOriginalConstructor()
            ->setMethods(array('setAuthentication'))
            ->getMock();

        $newEmail = new NewEmailWatcherDump();

        $watcher
            ->setFetchServer($fetchServer)
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();
    }

    public function testProcessBeforeInit()
    {

        $this->setExpectedException('\Exception');

        $newEmail = new NewEmailWatcherDump();

        $watcher = new Watcher();

        $watcher->setServer('server')
            ->setPort('port')
            ->setEmail('email')
            ->setProtocol('imap')
            ->setPassword('password')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->process();
    }


    public function testProcessNominal()
    {
        $watcher = $this->getMockBuilder('K3roulas\EmailWatcher\Watcher')
            ->setMethods(array('createServer', 'process'))
            ->getMock();

        $fetchServer = $this->getMockBuilder('Fetch\Server')
            ->disableOriginalConstructor()
            ->setMethods(array('setAuthentication'))
            ->getMock();

        $fetchServer->expects($this->once())
            ->method('setAuthentication');


        $watcher->expects($this->once())
            ->method('createServer')
            ->will($this->returnValue($fetchServer));

        $watcher->expects($this->once())
            ->method('process');

        $newEmail = new NewEmailWatcherDump();

        $watcher->setServer('server')
            ->setPort('port')
            ->setEmail('email')
            ->setProtocol('imap')
            ->setPassword('password')
            ->setFilename('filename')
            ->setNewEmailWatcher($newEmail)
            ->init();

        $watcher->process();
    }






}
 