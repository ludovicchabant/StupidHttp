<?php

class StupidHttp_Mock_WebServer extends StupidHttp_WebServer
{
    public $browserWasRun;

    public function __construct()
    {
        $this->browserWasRun = false;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    protected function runBrowser()
    {
        $this->browserWasRun = true;
    }
}

