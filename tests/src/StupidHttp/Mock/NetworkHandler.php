<?php

class StupidHttp_Mock_NetworkHandler extends StupidHttp_SocketNetworkHandler
{
    public $registered;
    public $connections;
    public $contents;

    public function __construct($contents = '')
    {
        $this->registered = false;
        $this->connections = array();
        $this->contents = $contents;
    }

    public function register()
    {
        $this->registered = true;
        $this->sockSendBufferSize = 256;
        $this->sockReceiveBufferSize = 256;
    }

    public function unregister()
    {
        $this->registered = false;
    }

    public function connect($options)
    {
        $c = time();
        $this->connections += $c;
        return $c;
    }

    public function getClientInfo($connection)
    {
        return array(
            'address' => 'test_host',
            'port' => '1234'
        );
    }

    public function disconnect($connection)
    {
        $i = array_search($connection, $this->connections);
        if ($i === false)
            throw new Exception("Connection '{$connection}' is unknown.");
        unset($this->connections[$i]);
    }

    public function write($connection, $data)
    {
        return strlen($data);
    }

    protected function readFromSocket($connection)
    {
        return $this->contents;
    }
}

