<?php

require_once "../vendor/autoload.php";

// Set the cookies params 
session_set_cookie_params([
    'lifetime' => 86400, //24 hours
    'path' => '/',
    'domain' => $_SERVER['SERVER_NAME'],
    'httponly' => true // It only accept requests by HTTP protocol, in order to avoid XSS attacks
]);
session_start();

define("BASE_PATH", "/var/www/html/");

use App\Controller\Controller;

$controller = new Controller;

$controller->route();
