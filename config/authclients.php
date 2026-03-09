<?php
use yii\authclient\clients\GitHub;
use yii\authclient\clients\Twitter;

$githubClientId = $_ENV['GITHUB_CLIENT_ID'] ?? null;
$githubClientSecret = $_ENV['GITHUB_CLIENT_SECRET'] ?? null;

$twitterConsumerKey = $_ENV['TWITTER_CONSUMER_KEY'] ?? null;
$twitterConsumerSecret = $_ENV['TWITTER_CONSUMER_SECRET'] ?? null;

$clients = [];

if ($githubClientId !== null && $githubClientSecret !== null) {
   $clients['github'] = [
        'class' => GitHub::class,
        'clientId' => $githubClientId,
        'clientSecret' => $githubClientSecret,
        'scope' => 'user:email',
    ];
}

if ($twitterConsumerKey !== null && $twitterConsumerSecret !== null) {
    $clients['twitter'] = [
        'class' => Twitter::class,
        'attributeParams' => [
            'include_email' => 'true'
        ],
        'consumerKey' => $twitterConsumerKey,
        'consumerSecret' => $twitterConsumerSecret,
    ];
}

return $clients;
