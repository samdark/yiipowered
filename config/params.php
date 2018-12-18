<?php

use codemix\localeurls\UrlManager;
use yii\helpers\ArrayHelper;
use yii\web\UrlNormalizer;

$languages = [
    'en' => ['en-US', 'English'],
    'fr' => ['fr-FR', 'French'],
    'ru' => ['ru-RU', 'Русский'],
    'pl' => ['pl-PL', 'Polski'],
    'vi' => ['vi-VN', 'Tiếng Việt'],
];

return [
    'adminEmail' => 'sam+yiipowered@rmcreative.ru',
    'notificationEmail' => 'noreply@yiipowered.com',
    'supportEmail' => 'noreply@yiipowered.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600 * 24 * 30,

    'languages' => $languages,

    'project.pagesize' => 9,
    'project.maxTopProjects' => 100,

    'image.size.full' => [1920, 1080],
    'image.size.thumbnail' => [402, 264],
    'image.size.big_thumbnail' => [760, 500],

    'user.avatar.size' => [200, 200],

    'debug.allowedIPs' => ['127.0.0.1'],
    
    'components.urlManager' => [
        'class' => UrlManager::class,
        'languages' => ArrayHelper::getColumn($languages, 0),
        'ignoreLanguageUrlPatterns' => [
            '~^site/auth~' => '~^auth~',
        ],
        'enableDefaultLanguageUrlCode' => true,
        'rules' => require __DIR__ . '/urls.php',
        'showScriptName' => false,

        'normalizer' => [
            'class' => UrlNormalizer::class,
        ],
    ],
    'components.queue' => [
        'class' => \yii\queue\db\Queue::class,
        'channel' => 'default'
    ],

    'HtmlPurifier.projectDescription' => [
        'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,p,ul,ol,li,b,i,u,strong,br,sub,sup,a[href]',
    ],
    'HtmlPurifier.projectComment' => [
        'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,p,ul,ol,li,b,i,u,strong,br,sub,sup,a[href],img[src]',
    ],
    
];
