<?php
/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var int $countTopProjects
 */

use \yii\widgets\ListView;

$this->title = Yii::t('project', 'Top {n}', ['n' => $countTopProjects]);
?>

<div class="project-index">
    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'emptyText' => Yii::t('project', 'Top {n} projects still empty', ['n' => $countTopProjects]),
            'itemOptions' => ['class' => 'project'],
            'itemView' => '_card'
        ]) ?>
    </div>
</div>
