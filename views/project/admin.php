<?php
use app\models\Project;
use \yii\widgets\ListView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Manage projects');
?>
<div class="row news-index">
    <div class="col-xs-12">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_view',
            'viewParams' => [
                'displayStatus' => true,
                'displayModeratorButtons' => true,
            ],
        ]) ?>
    </div>

</div>