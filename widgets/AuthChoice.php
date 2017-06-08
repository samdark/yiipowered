<?php

namespace app\widgets;

use Yii;
use yii\helpers\Html;

class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    /**
     * @param \yii\authclient\ClientInterface $client
     * @param string $text
     * @param array $htmlOptions
     * @return string
     * @throws \yii\base\InvalidConfigException
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
