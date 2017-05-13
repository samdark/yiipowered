<?php
/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Html;
use \yii\widgets\ListView;

$this->title = Yii::t('bookmark', 'Bookmarked projects');
?>
<div class="project-index">
    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'emptyText' => Yii::t('project', 'You have no bookmarked projects yet.'),
            'itemOptions' => ['class' => 'project'],
            'itemView' => '_card'
        ]) ?>
    </div>
</div>
