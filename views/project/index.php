<?php

use \yii\widgets\ListView;

/* @var $featuredProvider yii\data\ActiveDataProvider */
/* @var $newProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects built with Yii');
?>
<div class="project-index">
    <h1><?= Yii::t('project', 'Featured projects') ?></h1>
    
    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $featuredProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_card'
        ]) ?>
    </div>

    <h1><?= Yii::t('project', 'New projects') ?></h1>

    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $newProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_card'
        ]) ?>
    </div>

    <?= \yii\bootstrap\Html::a(Yii::t('project', 'More projects'), ['project/list'], ['class' => 'btn btn-default']) ?>
</div>
