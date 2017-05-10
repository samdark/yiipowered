<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $model app\models\Project */

$this->title = Yii::t('project', 'Update project {title}', [
    'title' => $model->title,
]);
?>

<div class="project-update">
    <ol class="wizard-progress">
        <li class="is-active" data-step="1">
            <?= Yii::t('project', 'General info') ?>
        </li>
        <li data-step="2">
            <?= Yii::t('project', 'Screenshots') ?>
        </li>
        <li data-step="3" class="progress__last">
            <?= Yii::t('project', 'Preview & Approve') ?>
        </li>
    </ol>

    <div class="form-box">
        <div class="form-wrapper">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
