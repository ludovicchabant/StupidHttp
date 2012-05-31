<?php

use org\bovigo\vfs\vfsStream;


class DriverTest extends PHPUnit_Framework_TestCase
{
    public function createDriver($networkContents = null, $mockConnections = array(), $documentRoot = null, $log = null)
    {
        $server = new StupidHttp_Mock_WebServer();
        $vfs = new StupidHttp_VirtualFileSystem($documentRoot);
        $handler = new StupidHttp_Mock_NetworkHandler($networkContents, $mockConnections);
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

    public function testSimpleVfsRequest()
    {
        vfsStream::setup('root', null, array(
            'robots.txt' => 'ROBOTS WELCOME'
        ));
        $driver = $this->createDriver(
            "GET /robots.txt HTTP/1.1\r\n\r\n",
            array(1),
            vfsStream::url("root")
        );
        $options = array(
            'list_directories' => true,
            'list_root_directory' => false, 
            'run_browser' => false,
            'keep_alive' => false,
            'timeout' => 4,
            'poll_interval' => 1,
            'show_banner' => true,
            'name' => null
        );
        $driver->runLimited($options);
        $runTime = date("D, d M Y H:i:s T");

        $handler = $driver->getNetworkHandler();
        $actual = $handler->bucket;
        $actualLines = explode("\n", $actual);

        $this->assertEquals('HTTP/1.1 200 OK', $actualLines[0]);

        $expectedBody = "ROBOTS WELCOME";
        $expectedBodyHash = md5($expectedBody);
        $expectedHeaders = array(
            'Server: StupidHttp',
            'Date: ' . $runTime,
            'Content-Length: ' . strlen($expectedBody),
            'Content-MD5: ' . base64_encode($expectedBodyHash),
            'Content-Type: text/plain',
            'ETag: ' . $expectedBodyHash,
            'Last-Modified: ' . $runTime,
            'Connection: close'
        );
        $i = 1;
        while (true)
        {
            if (strlen($actualLines[$i]) == 0)
                break;
            $this->assertContains(
                $actualLines[$i],
                $expectedHeaders
            );
            ++$i;
        }
        $this->assertEquals(
            $expectedBody,
            $actualLines[$i + 1]
        );
        $this->assertEquals($i + 2, count($actualLines));
    }
}

