<?php
/* @var $model \app\models\Project */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<a class="project-card <?= $model->getStatusClass() ?>" href="<?= Url::to(['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?>">
    <img class="img-responsive" src="<?= $model->getPrimaryImageThumbnailRelativeUrl() ?>" alt="">
    <h1><?= Html::encode($model->title) ?></h1>
</a>
