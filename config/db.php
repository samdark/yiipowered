<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'enableSchemaCache' => !$_ENV['APP_DEBUG'],
];
