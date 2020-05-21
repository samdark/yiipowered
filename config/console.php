<?php

use app\components\ApiFlash;
use app\components\ScreenshotLayerApi;
use yii\gii\Module;
use yii\mutex\MysqlMutex;
use yii\rbac\PhpManager;
use yii\caching\FileCache;
use yii\log\FileTarget;

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@webroot', dirname(__DIR__) . '/web');

$params = array_merge(
    require __DIR__ . '/params.php',
    is_file(__DIR__ . '/params-local.php') ? require __DIR__ . '/params-local.php' : []
);
$db = require __DIR__ . '/db.php';

return [
    'id' => 'yiipowered-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii', 'queue'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'templateFile' => '@app/components/migration/template.php',
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],
    ],
    'modules' => [
        //'gii' => Module::class,
    ],
    'components' => [
        'authManager' => [
            'class' => PhpManager::class,
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => array_merge(
            $params['components.urlManager'],
            [
                'baseUrl' => '',
                'hostInfo' => $params['siteAbsoluteUrl']
            ]
        ),
        'mutex' => MysqlMutex::class,
        'queue' => $params['components.queue'],
        'screenshotLayer' => [
            'class' => ScreenshotLayerApi::class,
            'secret' => $params['screenshotLayer.secret'],
            'accessKey' => $params['screenshotLayer.accessKey'],
        ],
        'apiFlash' => [
            'class' => ApiFlash::class,
            'accessKey' => $params['apiFlash.accessKey'],
        ],
    ],
    'params' => $params,
];
