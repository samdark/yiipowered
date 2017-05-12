<?php

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 */

use yii\helpers\Html;

$this->title = Yii::t('project', 'Add project');
?>

<div class="project-add">
    <ol class="wizard-progress">
        <li class="is-active" data-step="1">
            <?= Yii::t('project', 'General info') ?>
        </li>
        <li data-step="2">
            <?= Yii::t('project', 'Screenshots') ?>
        </li>
        <li data-step="3" class="progress__last">
            <?= Yii::t('project', 'Approve') ?>
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
