<?php

require '../lib/StupidHttp/StupidHttp_WebServer.php';

$server = new StupidHttp_WebServer();
$server->on('GET', '/')
       ->call(function($r) { echo 'Hello from StupidHttp!'; });
$server->onPattern('GET', '/hello/(.+)')
       ->call(function($r, $m) { echo 'Hello, ' . $m[1]; });
$server->run();
