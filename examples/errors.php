<?php

require '../lib/StupidHttp/Autoloader.php';
StupidHttp_Autoloader::register();

$server = new StupidHttp_WebServer();
$server->on('GET', '/')
    ->call(function($c) {
        echo '<html><body>';
        echo 'Go to <code>/xxx</code> where <code>xxx</code> is an <a href="http://en.wikipedia.org/wiki/List_of_HTTP_status_codes">HTTP status code</a>, and see how your browser renders it.';
        echo '</body></html>';
    });
$server->onPattern('GET', '/(\d{3})')
    ->call(function($c, $m) {
        $c->getResponse()->setStatus(intval($m[1])); 
    });
$server->run(array('run_browser' => true));
