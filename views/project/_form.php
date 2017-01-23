<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 * @var yii\widgets\ActiveForm $form
 */

\app\assets\MarkdownEditorAsset::register($this);
?>

<?php $form = ActiveForm::begin(['id' => 'news-form']) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
<?= $form->field($model, 'description')->textarea() ?>

<?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'status')->dropDownList(\app\models\Project::statuses()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('project', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>