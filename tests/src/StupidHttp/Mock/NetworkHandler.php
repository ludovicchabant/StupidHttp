<?php

class StupidHttp_Mock_NetworkHandler extends StupidHttp_SocketNetworkHandler
{
    public $registered;
    public $connections;
    public $contents;
    public $bucket;

    public function __construct($contents = '', $connections = array())
    {
        $this->registered = false;
        $this->contents = $contents;
        if (!is_array($connections))
            $connections = array($connections);
        $this->connections = $connections;
        $this->bucket = '';
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

    public function connect(array $connections, $options)
    {
        return $this->connections;
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
    }

    public function write($connection, $data)
    {
        $this->bucket .= $data;
        return strlen($data);
    }

    protected function readFromSocket($connection)
    {
        return $this->contents;
    }
}

