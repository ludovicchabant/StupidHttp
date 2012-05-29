<?php

require '../lib/StupidHttp/Autoloader.php';
StupidHttp_Autoloader::register();

$documentRoot = getcwd();
$argv = $_SERVER['argv'];
if (count($argv) == 2)
    $documentRoot = $argv[1];

$server = new StupidHttp_WebServer($documentRoot);
$server->run(array('run_browser' => true));

