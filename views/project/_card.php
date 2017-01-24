<?php
/* @var $model \app\models\Project */
use yii\helpers\Html;

?>

<div class="project-card col-xs-3 well">
    <h1>
        <?= Html::a(Html::encode($model->title), ['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?>
    </h1>
</div>