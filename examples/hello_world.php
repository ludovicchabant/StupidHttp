<?php

require '../lib/StupidHttp/Autoloader.php';
StupidHttp_Autoloader::register();

$server = new StupidHttp_WebServer();
$server->on('GET', '/')
    ->call(function($r) {
        echo '<html><body>';
        echo '<p>Hello from StupidHttp!</p>'; 
        echo '<form name="input" action="/hello/" method="post">';
        echo 'What\'s your name?: <input type="text" name="name" />';
        echo '</form>';
        echo '<p>Also: say hello to <a href="/hello/Bob">Bob</a>!</p>';
        echo '</body></html>';
    });
$server->onPattern('GET', '/hello/(.+)')
    ->call(function($c, $m)
    {
        if ($m[1])
        {
            echo '<html><body>';
            echo 'Hello, ' . $m[1] . '<br/>';
            echo '(brought to you via GET)';
            echo '</body></html>';
        }
    });
$server->onPattern('POST', '/hello')
    ->call(function($c)
    {
        $data = $c->getRequest()->getFormData();
        echo '<html><body>';
        echo 'Hello, ' . $data['name'] . '<br/>';
        echo '(brought to you via POST)';
        echo '</body></html>';
    });
$server->run(array('run_browser' => true));

