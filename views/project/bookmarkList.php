<?php
/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Html;
use \yii\widgets\ListView;

$this->title = Yii::t('bookmark', 'Favorite projects');
?>
<div class="project-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_card'
        ]) ?>
    </div>
</div>
