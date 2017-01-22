<?php

use \yii\widgets\ListView;
use yii\bootstrap\Alert;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects');
?>
<div class="row news-index">
    <?php if (Yii::$app->session->hasFlash('project.project_successfully_added')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => Yii::t('project', 'Project added!'),
        ]);
    } ?>
    <div class="col-xs-12">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_view'
        ]) ?>
    </div>

</div>