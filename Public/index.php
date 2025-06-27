<?php
define("BASE_PATH", "/var/www/html/");

require_once "../vendor/autoload.php";


use App\Controller\Controller;

$controller = new Controller;

$controller->route();
