<?php

use app\components\UserPermissions;
use app\models\Project;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 * @var yii\widgets\ActiveForm $form
 */

\app\assets\MarkdownEditorAsset::register($this);
?>

<?php $form = ActiveForm::begin(['id' => 'project-form']) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
<?= $form->field($model, 'description')->textarea(['class' => 'markdown-editor']) ?>

<?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

<div class="well">
    <?= $form->field($model, 'is_opensource')->checkbox() ?>
    <?= $form->field($model, 'source_url')->textInput(['maxlength' => 255]) ?>
</div>

<?php if (Yii::$app->user->can(UserPermissions::MANAGE_PROJECTS)): ?>
    <fieldset class="well">
        <?= $form->field($model, 'is_featured')->checkbox() ?>
        <?= $form->field($model, 'status')->dropDownList(Project::statuses()) ?>
    </fieldset>
<?php endif ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('project', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>