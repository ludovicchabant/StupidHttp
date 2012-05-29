<?php


class StupidHttp_Tests_WebRequestTest extends PHPUnit_Framework_TestCase
{
    public function methodUriVersionParsingDataProvider()
    {
        return array(
            array(
                'GET /index.html HTTP/1.1',
                'GET',
                '/index.html',
                'HTTP/1.1'
            ),
            array(
                'POST /postback/something.aspx?mode=1 HTTP/1.1',
                'POST',
                '/postback/something.aspx?mode=1',
                'HTTP/1.1'
            ),
            array(
                'GET /old/stuff HTTP/1.0',
                'GET',
                '/old/stuff',
                'HTTP/1.0'
            ),
            array(
                'GET /directory/ HTTP/1.1',
                'GET',
                '/directory/',
                'HTTP/1.1'
            ),
        );
    }

    /**
     * @dataProvider methodUriVersionParsingDataProvider
     */
    public function testMethodUriVersionParsing($raw, $method, $uri, $version)
    {
        $server = array();
        $request = new StupidHttp_WebRequest($server, explode('\n', $raw));
        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals($uri, $request->getUri());
        $this->assertEquals($version, $request->getVersion());
    }
    
    public function requestParsingDataProvider()
    {
        return array(
            array(
                'GET /index.html HTTP/1.1',
                array()
            ),
            array(
                'GET /index.html HTTP/1.1\n'.
                'Blah: something',
                array(
                    'Blah' => 'something'
                )
            ),
            array(
                'GET /index.html HTTP/1.1\n'.
                'Blah: something\n'.
                'Foo: bar-bar',
                array(
                    'Blah' => 'something',
                    'Foo' => 'bar-bar'
                )
            ),
            array(
                'GET /index.html HTTP/1.1\n'.
                'Content-Type: text/html\n'.
                'Content-MD5: Q2hlY2sgSW50ZWdyaXR5IQ==\n'.
                'From: user@example.org',
                array(
                    'Content-Type' => 'text/html',
                    'Content-MD5' => 'Q2hlY2sgSW50ZWdyaXR5IQ==',
                    'From' => 'user@example.org'
                )
            )
        );
    }

    /**
     * @dataProvider requestParsingDataProvider
     */
    public function testRequestParsing($raw, $headers)
    {
        $server = array();
        $request = new StupidHttp_WebRequest($server, explode('\n', $raw));
        $this->assertEquals($headers, $request->getHeaders());
    }

    /**
     * @expectedException StupidHttp_WebException
     */
    public function testEmptyRequest()
    {
        $server = array();
        $request = new StupidHttp_WebRequest($server, array());
    }

    /**
     * @expectedException StupidHttp_WebException
     */
    public function testInvalidRequest()
    {
        $server = array();
        $request = new StupidHttp_WebRequest($server, array('GET something HPPT/1.0'));
    }
}
