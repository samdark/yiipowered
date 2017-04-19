<?php


namespace app\modules\api1\components;


use app\modules\api1\models\User;

class Controller extends \yii\rest\Controller
{
    /**
     * Returns current user.
     * Should be used in order to support token-less auth via web session that is handy for AJAX.
     *
     * @return null|User
     */
    protected function getCurrentUser()
    {
        /** @var User $user */
        $user = \Yii::$app->getUser()->getIdentity();
        return $user;
    }
}