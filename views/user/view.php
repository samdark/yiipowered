<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $authClients \yii\authclient\ClientInterface[] */

$this->title = $model->username;

use \yii\widgets\ListView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */

?>
<div class="row user-view">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-8 clearfix">
                <div class="user-view-avatar">
                    <?= \app\widgets\Avatar::widget([
                        'user' => $model,
                        'size' => 165,
                    ]) ?>
                </div>

                <h1><?= Html::encode($this->title) ?></h1>
                <?php if ($model->getGithubProfileUrl() !== null): ?>
                    <h3><?= Html::a(Html::encode($model->getGithubProfileUrl()), $model->getGithubProfileUrl())?></h3>
                <?php endif ?>

                <?php if (Yii::$app->user->can('manage_users')): ?>
                    <p>
                        <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                <?php endif ?>
            </div>
            <?php if (count($authClients) > 0): ?>
            <div class="col-sm-4">
                <div class="well well-sm">
                    <h2>Connect extra profiles:</h2>
                    <?= yii\authclient\widgets\AuthChoice::widget([
                        'baseAuthUrl' => ['site/auth'],
                        'popupMode' => false,
                        'clients' => $authClients,
                    ]) ?>
                </div>
            </div>
            <?php endif ?>
        </div>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'emptyText' => Yii::t('user', 'You haven\'t added any projects yet.'),
            'itemOptions' => [
                'class' => 'item',
            ],
            'itemView' => '/project/_card',
            'viewParams' => [
                'displayStatus' => true,
                'displayUser' => false,
            ],
        ]) ?>
    </div>
</div>
