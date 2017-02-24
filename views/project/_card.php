<?php
/* @var $model \app\models\Project */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<a class="project-card col-xs-6 col-sm-4 <?= $model->getStatusClass() ?>" href="<?= Url::to(['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?>">
    <img class="img-responsive" src="<?= $model->getPrimaryImageUrl() ?>" alt="">
    <h1><?= Html::encode($model->title) ?></h1>
</a>
