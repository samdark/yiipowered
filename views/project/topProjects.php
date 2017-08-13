<?php
/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var int $maxTopProjects
 */

use \yii\widgets\ListView;

$this->title = Yii::t('project', 'Top {n}', ['n' => $maxTopProjects]);
?>

<div class="project-index">
    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'emptyText' => Yii::t('project', 'Top {n} projects is still empty', ['n' => $maxTopProjects]),
            'itemOptions' => ['class' => 'project'],
            'itemView' => '_card'
        ]) ?>
    </div>
</div>
