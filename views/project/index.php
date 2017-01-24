<?php

use \yii\widgets\ListView;

/* @var $featuredProvider yii\data\ActiveDataProvider */
/* @var $newProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects built with Yii');
?>
<div class="row project-index">
    <div class="col-xs-12">
        <h1><?= Yii::t('project', 'Featured projects') ?></h1>

        <?= ListView::widget([
            'dataProvider' => $featuredProvider,
            'layout' => '{items}',
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_view'
        ]) ?>

        <h1><?= Yii::t('project', 'New projects') ?></h1>

        <?= ListView::widget([
            'dataProvider' => $newProvider,
            'layout' => '{items}',
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_view'
        ]) ?>
    </div>

</div>