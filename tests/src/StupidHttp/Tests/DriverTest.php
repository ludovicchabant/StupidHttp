<?php

class DriverTest extends PHPUnit_Framework_TestCase
{
    public function createDriver($networkContents = null, $documentRoot = null, $log = null)
    {
        $server = new StupidHttp_Mock_WebServer();
        $vfs = new StupidHttp_VirtualFileSystem($documentRoot);
        $handler = new StupidHttp_Mock_NetworkHandler($networkContents);
        $driver = new StupidHttp_Driver($server, $vfs, $handler, $log);
        $server->setDriver($driver);
        return $driver;
    }

    public function testAddRequestHandler()
    {
        $driver = $this->createDriver();
        $this->assertEmpty($driver->getRequestHandlers());
        $handler = 42;
        $driver->addRequestHandler('FOO', $handler);
        $this->assertEquals(
            array('FOO' => array($handler)),
            $driver->getRequestHandlers()
        );
    }

    public function testSetPreprocessor()
    {
        $driver = $this->createDriver();
        $this->assertNull($driver->getPreprocessor());
        $p = function () {};
        $driver->setPreprocessor($p);
        $this->assertEquals($p, $driver->getPreprocessor());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidPreprocessor()
    {
        $driver = $this->createDriver();
        $this->assertNull($driver->getPreprocessor());
        $driver->setPreprocessor(42);
    }
}

