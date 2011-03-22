<?php

require '../lib/StupidHttp/StupidHttp_WebServer.php';
require '../lib/StupidHttp/StupidHttp_ConsoleLog.php';

date_default_timezone_set('America/Los_Angeles');

$server = new StupidHttp_WebServer();
$server->setLog(new StupidHttp_ConsoleLog());
$server->on('GET', '/')
       ->call(function($c) { echo 'Go to /xxx where xxx is an HTTP error number.'; });
$server->onPattern('GET', '/(\d{3})')
       ->call(function($c, $m) { $c->getResponse()->setStatus(intval($m[1])); });
$server->run(array('run_browser' => true));
