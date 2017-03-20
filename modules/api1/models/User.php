<?php


namespace app\modules\api1\models;


use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

class User extends \app\models\User implements Linkable
{
    public function fields()
    {
        return [
            'id' => 'id',
            'username' => 'username',
            'fullname' => 'fullname',
            'github' => 'github',
            'twitter' => 'twitter',
            'facebook' => 'facebook',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['/user/view', 'id' => $this->id], 'http'),
        ];
    }
}