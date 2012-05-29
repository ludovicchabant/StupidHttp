<?php

require '../lib/StupidHttp/Autoloader.php';
StupidHttp_Autoloader::register();

function print_request($r)
{
    echo '<html><body>';
    echo '<h1>Request</h1>';
    echo '<p>Below are the various parts of your request.</p>'; 

    echo '<h3>Server Variables</h3>';
    echo '<pre>';
    print_r($r->getServerVariables());
    echo '</pre>';
    echo '<h3>Query Variables</h3>';
    echo '<pre>';
    print_r($r->getQueryVariables());
    echo '</pre>';
    echo '<h3>Body</h3>';
    echo '<pre>';
    print_r($r->getBody());
    echo '</pre>';

    echo '<h1>Forms</h1>';
    echo '<h2>GET</h2>';
    echo '<p>Here is a GET form to see what kind of request it will create.</p>';
    echo '<form name="input_get" action="/" method="get">';
    echo 'First Name: <input type="text" name="first_name" /><br/>';
    echo 'Last Name: <input type="text" name="last_name" /><br/>';
    echo 'Are You Awesome? <input type="checkbox" name="is_awesome" /><br/>';
    echo 'Your Credit Card Number: <input type="password" name="password" /><br/>';
    echo '<input type="submit" value="Send!" name="submit_btn" />';
    echo '</form>';

    echo '<h2>POST</h2>';
    echo '<p>Here is a POST form to see what kind of request it will create.</p>';
    echo '<form name="input_post" action="/" method="post">';
    echo 'First Name: <input type="text" name="first_name" /><br/>';
    echo 'Last Name: <input type="text" name="last_name" /><br/>';
    echo 'Are You Awesome? <input type="checkbox" name="is_awesome" /><br/>';
    echo 'Your Credit Card Number: <input type="password" name="password" /><br/>';
    echo '<input type="submit" value="Send!" name="submit_btn" />';
    echo '</form>';
    echo '</body></html>';
}

$server = new StupidHttp_WebServer();
$server->onPattern('GET', '.*')
    ->call(function($c) {
        print_request($c->getRequest());
    });
$server->onPattern('POST', '.*')
    ->call(function($c) {
        print_request($c->getRequest());
    });
$server->run(array('run_browser' => true));

