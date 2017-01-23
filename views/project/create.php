<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 * @var ActiveForm $form
 */

\app\assets\MarkdownEditorAsset::register($this);
?>

<div class="project-add">

    <?php $form = ActiveForm::begin(['id' => 'news-add']) ?>


    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'yii_version')->dropDownList(\app\models\Project::versions()) ?>


    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end() ?>

</div>
