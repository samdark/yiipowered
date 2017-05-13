<?php

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $authClients \yii\authclient\ClientInterface[] */

$this->title = $model->username;

use \yii\widgets\ListView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */

?>
<div class="user-view">
    <div class="information">
        <div class="avatar">
            <?= \app\widgets\Avatar::widget([
                'user' => $model,
                'size' => 165,
            ]) ?>
        </div>

        <div class="bio">
            <h1><?= Html::encode($this->title) ?></h1>

            <?php if (count($authClients) > 0): ?>
                <?php $authAuthChoice = AuthChoice::begin([
                    'baseAuthUrl' => ['site/auth'],
                    'popupMode' => false,
                    'clients' => $authClients,
                ]); ?>
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <h3>
                        <?= $authAuthChoice->clientLink(
                            $client,
                            Yii::t('user', 'Connect with {servicename}', ['servicename' => $client->getName()])
                        ) ?>
                    </h3>
                <?php endforeach; ?>
                <?php AuthChoice::end(); ?>
            <?php endif ?>

            <?php if ($model->getGithubProfileUrl() !== null): ?>
                <h3><?= Html::a(Html::encode($model->getGithubProfileUrl()), $model->getGithubProfileUrl()) ?></h3>
            <?php endif ?>

            <?php if (Yii::$app->user->can('manage_users')): ?>
                <div class="controls">
                    <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="projects">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'projects-flow'],
            'layout' => '{items}{pager}',
            'emptyText' => Yii::t('user', 'Haven\'t added any projects yet.'),
            'itemOptions' => ['class' => 'project'],
            'itemView' => '/project/_card',
            'viewParams' => [
                'displayStatus' => true,
                'displayUser' => false,
            ],
        ]) ?>
    </div>
</div>
