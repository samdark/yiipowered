<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\rbac\PhpManager;
use yii\caching\FileCache;
use app\models\User;
use yii\swiftmailer\Mailer;
use yii\log\FileTarget;
use yii\i18n\PhpMessageSource;
use yii\authclient\Collection;
use yii\gii\Module;
use baibaratsky\yii\rollbar\web\ErrorHandler;

$params = array_merge(
    require __DIR__ . '/params.php',
    is_file(__DIR__ . '/params-local.php') ? require __DIR__ . '/params-local.php' : []
);

$config = [
    'id' => 'yiipowered',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'project/index',
    'bootstrap' => ['log'],
    'aliases' => [
        'bower' => '@vendor/bower-asset',
        'npm' => '@vendor/npm-asset',
    ],
    'container' => [
        'definitions' => [
            ActionColumn::class => [
                'header' => 'Action',
                'headerOptions' => [
                    'class' => 'text-center col-md-1',
                ],
                'contentOptions' => [
                    'class' => 'text-center text-nowrap',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-default btn-xs',
                ],
                'template' => '{update} {delete}',
            ],
            GridView::class => [
                'pager' => [
                    'options' => [
                        'class' => 'pagination pull-right',
                    ],
                ],
                'tableOptions' => [
                    'class' => 'table table-hover'
                ],
                'options' => [
                    'class' => 'panel panel-default'
                ],
                'layout' => '{items}{pager}',
            ]
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => PhpManager::class,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => require __DIR__ . '/key.php',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_DEBUG,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require __DIR__ . '/db.php',
        'mutex' => \yii\mutex\MysqlMutex::class,
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => PhpMessageSource::class,
                    'fileMap' => [
                        'project' => 'project.php',
                        'user' => 'user.php',
                    ],
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => Collection::class,
            'clients' => require __DIR__ . '/authclients.php',
        ],
        'urlManager' => $params['components.urlManager'],
        'queue' => $params['components.queue'],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => [
                \yii\authclient\widgets\AuthChoiceStyleAsset::class => false
            ]
        ],
        'checker' => [
            'class' => \app\checkers\CheckerService::class,
            'checkers' => require __DIR__ . '/checkers.php',
        ],
    ],
    'params' => $params,
    'modules' => [
        'api1' => [
            'class' => \app\modules\api1\Module::class,
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        'allowedIPs' => $params['debug.allowedIPs']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = Module::class;
} else {
    $config['components']['rollbar'] = require __DIR__  . '/rollbar.php';
    $config['bootstrap'][] = 'rollbar';
    $config['components']['errorHandler']['class'] = ErrorHandler::class;
}

return $config;
