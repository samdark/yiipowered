<?php

namespace app\widgets;

use Yii;
use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;

class AuthChoise extends AuthChoice
{
    /**
     * @param \yii\authclient\ClientInterface $client
     * @param null $text
     * @param array $htmlOptions
     * @return string
     */
    public function clientLink($client, $text = null, array $htmlOptions = [])
    {
        if ($text === null) {
            $content = Yii::t('app', 'Sign in with {service}', ['service' => $client->getTitle()]);
            $text = Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]) . $content;
        }

        return parent::clientLink($client, $text, $htmlOptions);
    }
}
