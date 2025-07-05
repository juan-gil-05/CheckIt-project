<?php
require_once BASE_PATH . '/vendor/autoload.php';

// File to initilaize env variables with the vlucas/phpdotenv library
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
return $_ENV;