<?php

namespace K3roulas\EmailWatcher\Test\LastUidPersist;


use K3roulas\EmailWatcher\LastUidPersist\LastUidPersistFile;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class LastUidPersistFileTest extends \PHPUnit_Framework_TestCase
{

    public function initRoot()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('home'));
    }

    public function testSetLastUidCorrectly()
    {
        $this->initRoot();

        $file = vfsStream::url('home/lastui.json');

        $lastUidPersistFile = new LastUidPersistFile($file);
        $lastUidPersistFile->setLastUid(12);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('lastui.json'), 'The file is not created' );
        $this->assertTrue(file_get_contents($file) == json_encode(array('lastUid' => 12)), 'The file content is not correct');

    }

    public function testGetLastUidCorrectly()
    {
        $this->initRoot();

        $file = vfsStream::url('home/lastui.json');
        file_put_contents($file, json_encode(array('lastUid' => 12)));

        $lastUidPersistFile = new LastUidPersistFile($file);
        $this->assertTrue($lastUidPersistFile->getLastUid() == 12, 'The result is incorrect');
    }


}
 