<?php
include "OauthServer.php";
include "GitlabServer.php";
include "FacebookServer.php";
$SERVERS = [
    OauthServer::class,
    GitlabServer::class,
    FacebookServer::class
];

function home()
{
    global $SERVERS;

    foreach ($SERVERS as $serverClass) {
        $server = new $serverClass;
        $server->displayLink();
    }
}

function callback()
{
    $provider = new $_GET['state']();
    $provider->getInfosClient();
}

// Router
$route = strtok($_SERVER['REQUEST_URI'], '?');
switch ($route) {
    case '/':
        home();
        break;
    case '/success':
        callback();
        break;
}
