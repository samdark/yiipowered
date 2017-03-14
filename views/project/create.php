<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 */

$this->title = Yii::t('project', 'Add project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('project', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="project-add">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
