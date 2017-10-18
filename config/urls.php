<?php
use yii\web\UrlRule;

return [
    'bookmarks' => 'project/bookmarks',
    'projects' => 'project/list',
    'projects/<id:\d+>/<slug>' => 'project/view',
    'top-100' => 'project/top-projects',
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
        'class' => \yii\rest\UrlRule::class,
        'controller' => ['1.0/projects' => 'api1/project'],
        'only' => ['index', 'view', 'update', 'vote', 'delete'],
        'prefix' => 'api',
        'extraPatterns' =>  ['PUT,PATCH {id}/vote' => 'vote'],
        'ruleConfig' => [
            'class' => UrlRule::class,
            'defaults' => [
                'expand' => 'users',
            ]
        ],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'controller' => ['1.0/users' => 'api1/user'],
        'only' => ['index', 'view'],
        'prefix' => 'api',
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'controller' => ['1.0/bookmarks' => 'api1/bookmark'],
        'only' => ['index', 'create', 'delete'],
        'prefix' => 'api',
        'ruleConfig' => [
            'class' => UrlRule::class,
            'defaults' => [
                'expand' => 'project',
            ]
        ],
    ],
];
