<?php

defined('YII_DEBUG') or define('YII_DEBUG', $_ENV['APP_DEBUG']);
defined('YII_ENV') or define('YII_ENV', $_ENV['APP_ENV']);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

new yii\web\Application($config)->run();
