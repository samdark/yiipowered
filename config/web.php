<?php

$params = array_merge(
    require(__DIR__ . '/params.php'),
    is_file(__DIR__ . '/params-local.php') ? require(__DIR__ . '/params-local.php') : []
);

$languages = [];
foreach ($params['languages'] as $id => $data) {
    $languages[$id] = $data[0];
}

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
            'yii\grid\ActionColumn' => [
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
            'yii\grid\GridView' => [
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
                'layout' => "{items}{pager}",
            ]
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => require __DIR__ . '/key.php',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_DEBUG,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'project' => 'project.php',
                        'user' => 'user.php',
                    ],
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => require __DIR__ . '/authclients.php',
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => $languages,
            'ignoreLanguageUrlPatterns' => [
                '~^site/auth~' => '~^auth~',
            ],
            'enableDefaultLanguageUrlCode' => true,

            'enablePrettyUrl' => true,
            'rules' => require __DIR__ . '/urls.php',
            'showScriptName' => false,

            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => false,
            'bundles' => [
                \yii\authclient\widgets\AuthChoiceStyleAsset::class => false
            ]
        ],
    ],
    'params' => $params,
    'modules' => [
        'api1' => [
            'class' => 'app\modules\api1\Module',
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
    $config['modules']['gii'] = 'yii\gii\Module';
} else {
    $config['bootstrap'][] = 'rollbar';
    $config['components']['errorHandler']['class'] = 'baibaratsky\yii\rollbar\web\ErrorHandler';
}

return $config;
