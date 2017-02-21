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

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <fieldset class="well">
                <h2>General</h2>

                <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
                <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
                <?= $form->field($model, 'tagValues')->textInput() ?>
            </fieldset>
        </div>

        <div class="col-xs-12 col-sm-6">
            <fieldset class="well">
                <h2>Source</h2>

                <?= $form->field($model, 'is_opensource')->checkbox() ?>
                <?= $form->field($model, 'source_url')->textInput(['maxlength' => 255]) ?>
            </fieldset>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'description')->textarea(['class' => 'markdown-editor']) ?>
        </div>
    </div>

    <div class="row">
        <?php /*
        <div class="col-xs-6">
            <div class="well">
                <h2>Team</h2>
            </div>
        </div>
        */ ?>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <fieldset class="well">
                <?php if (UserPermissions::canManageProjects()): ?>
                    <?= $form->field($model, 'is_featured')->checkbox() ?>
                <?php endif ?>
                <?= $form->field($model, 'status')->dropDownList(Project::statuses()) ?>
            </fieldset>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('project', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>