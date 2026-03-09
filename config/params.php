<?php

use codemix\localeurls\UrlManager;
use yii\helpers\ArrayHelper;
use yii\queue\db\Queue;
use yii\web\UrlNormalizer;

$languages = [
    'en' => ['en-US', 'English'],
    'fr' => ['fr-FR', 'Français'],
    'ru' => ['ru-RU', 'Русский'],
    'pl' => ['pl-PL', 'Polski'],
    'vi' => ['vi-VN', 'Tiếng Việt'],
    'de' => ['de-DE', 'Deutsch'],
];

$params = [
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
        'class' => Queue::class,
        'channel' => 'default'
    ],

    'HtmlPurifier.projectDescription' => [
        'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,p,ul,ol,li,b,i,u,strong,br,sub,sup,a[href]',
    ],
    'HtmlPurifier.projectComment' => [
        'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,p,ul,ol,li,b,i,u,strong,br,sub,sup,a[href],img[src]',
    ],
];

$appAbsoluteUrl = $_ENV['APP_ABSOLUTE_URL'] ?? null;
if ($appAbsoluteUrl) {
    $params['siteAbsoluteUrl'] = $appAbsoluteUrl;
}

/*
 * @see https://apps.twitter.com/app/new
 *
 * After creating an app you need to fill accessToken and accessTokenSecret:
 *
 * Open App -> Keys and Access Tokens -> You Access Token -> Create my access token
 */
$twitterConsumerKey = $_ENV['TWITTER_CONSUMER_KEY'] ?? null;
if ($twitterConsumerKey !== null) {
    $params['twitter.consumerKey'] = $twitterConsumerKey;
}

$twitterConsumerSecret = $_ENV['TWITTER_CONSUMER_SECRET'] ?? null;
if ($twitterConsumerSecret !== null) {
    $params['twitter.consumerSecret'] = $twitterConsumerSecret;
}

$twitterAccessToken = $_ENV['TWITTER_ACCESS_TOKEN'] ?? null;
if ($twitterAccessToken !== null) {
    $params['twitter.accessToken'] = $twitterAccessToken;
}

$twitterAccessTokenSecret = $_ENV['TWITTER_ACCESS_TOKEN_SECRET'] ?? null;
if ($twitterAccessTokenSecret !== null) {
    $params['twitter.accessTokenSecret'] = $twitterAccessTokenSecret;
}


// get these at https://screenshotlayer.com/dashboard
$screenshotLayerSecret = $_ENV['SCREENSHOT_LAYER_SECRET'] ?? null;
if ($screenshotLayerSecret !== null) {
    $params['screenshotLayer.secret'] = $screenshotLayerSecret;
}

$screenshotLayerAccessKey = $_ENV['SCREENSHOT_LAYER_ACCESS_KEY'] ?? null;
if ($screenshotLayerAccessKey !== null) {
    $params['screenshotLayer.accessKey'] = $screenshotLayerAccessKey;
}

// get these at https://apiflash.com/documentation#access
$apiFlashAccessKey = $_ENV['API_FLASH_ACCESS_KEY'] ?? null;
if ($apiFlashAccessKey !== null) {
    $params['apiFlash.accessKey'] = $apiFlashAccessKey;
}

return $params;