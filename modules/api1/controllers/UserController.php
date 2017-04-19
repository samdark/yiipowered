<?php

namespace app\modules\api1\controllers;

use app\modules\api1\components\Controller;
use app\modules\api1\models\User;
use yii\data\ActiveDataProvider;

class UserController extends Controller
{
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => User::find()->active(),
        ]);
    }

    public function actionView($id)
    {
        return User::find()->where(['id' => $id])->active()->one();
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
        ];
    }
}
