<?php

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use app\widgets\Avatar;
use app\components\UserPermissions;
use \yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $authClients \yii\authclient\ClientInterface[] */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */

$this->title = $model->username;
?>
<div class="user-view">
    <div class="information">
        <div class="avatar">
            <?php if($model->avatar) { ?>
                <?= Html::img($model->getAvatarImage(), ['alt' => $model->username, 'class' => 'img-thumbnail']) ?>
            <?php } else { ?>
                <?= Avatar::widget([
                    'user' => $model,
                    'size' => 165,
                ]) ?>
            <?php } ?>
        </div>

        <div class="bio">
            <h1>
                <?= Html::encode($this->title) ?>
                <small><?= Html::encode($model->fullname) ?></small>
            </h1>

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

            <?php if (UserPermissions::canManageUser($model)): ?>
                <div class="controls">
                    <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

                    <?php if(Yii::$app->user->can(UserPermissions::MANAGE_USERS)) { ?>
                        <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php } ?>
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
