<?php

use \yii\widgets\ListView;

/* @var $featuredProvider yii\data\ActiveDataProvider */
/* @var $newProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects built with Yii');
?>
<div class="project-index">
    <div class="row">
        <h1><?= Yii::t('project', 'Featured projects') ?></h1>

        <?= ListView::widget([
            'dataProvider' => $featuredProvider,
            'layout' => '{items}',
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_card'
        ]) ?>
    </div>

    <div class="row">
        <h1><?= Yii::t('project', 'New projects') ?></h1>

        <?= ListView::widget([
            'dataProvider' => $newProvider,
            'layout' => '{items}',
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_card'
        ]) ?>
    </div>

    <div class="row">
        <?= \yii\bootstrap\Html::a(Yii::t('project', 'More projects'), ['project/list'], ['class' => 'btn btn-default']) ?>
    </div>
</div>