<?php
return [
    'projects' => 'project/list',
    'projects/<id:\d+>/<slug>' => 'project/view',
    'user' => 'user/view',

    'about' => 'site/about',
    'logout' => 'site/logout',
    'login' => 'site/login',
    'signup' => 'site/signup',
    'auth' => 'site/auth',

    // API
    'api/1.0' => 'api1/docs/index',
    'api' => 'api1/docs/index',
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['1.0/projects' => 'api1/project'],
        'only' => ['index', 'view'],
        'prefix' => 'api',
        'ruleConfig' => [
            'class' => 'yii\web\UrlRule',
            'defaults' => [
                'expand' => 'users',
            ]
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['1.0/users' => 'api1/user'],
        'only' => ['index', 'view'],
        'prefix' => 'api',
    ],
];
