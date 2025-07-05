<?php
require_once BASE_PATH . '/vendor/autoload.php';

// File to initilaize env variables with the vlucas/phpdotenv library
use Dotenv\Dotenv;


if (file_exists(BASE_PATH . '/.env')) {
    // To charge .env if exists (in local)
    $dotenv = Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
    return $_ENV;
}
// else, use the getenv 
return [
    // Mysql
    'MYSQL_DATABASE' => getenv('MYSQL_DATABASE'),
    'MYSQL_USER' => getenv('MYSQL_USER'),
    'MYSQL_PASSWORD' => getenv('MYSQL_PASSWORD'),
    'MYSQL_PORT' => getenv('MYSQL_PORT'),
    'MYSQL_HOST' => getenv('MYSQL_HOST'),
    // MongoDB
    'MONGO_INITDB_ROOT_USERNAME' => getenv('MONGO_INITDB_ROOT_USERNAME'),
    'MONGO_INITDB_ROOT_PASSWORD' => getenv('MONGO_INITDB_ROOT_PASSWORD'),
    'MONGO_PORT' => getenv('MONGO_PORT'),
    'MONGO_HOST' => getenv('MONGO_HOST'),
    // Encrypter Key
    'ENCRYPTER_KEY' => getenv('ENCRYPTER_KEY')
];