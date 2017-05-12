<?php

use app\components\UserPermissions;
use app\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 * @var yii\widgets\ActiveForm $form
 */

\app\assets\MarkdownEditorAsset::register($this);
$autocompleteTagUrl = Url::toRoute(['/project/autocomplete-tags']);
?>

<?php $form = ActiveForm::begin(['id' => 'project-form']) ?>

<div class="general">
    <h2><?= Yii::t('project', 'General') ?></h2>

    <?= $form->field($model, 'title')
        ->textInput(['maxlength' => 50, 'placeholder' => $model->getAttributeLabel('title')])
        ->label(false) ?>
    <?= $form->field($model, 'url')
        ->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('url')])
        ->label(false) ?>
    <?= $form->field($model, 'yii_version')->dropDownList(Project::versions())->label(false) ?>


    <?= $form->field($model, 'tagValues')->widget(\yii\jui\AutoComplete::classname(), [
        'options' => [
            'class' => 'form-control',
            'placeholder' => $model->getAttributeLabel('tagValues')
        ],
        'clientOptions' => [
            'source' => new JsExpression("function(request, response) {
                $.getJSON( \"$autocompleteTagUrl\", {
                    term: request.term.split( /,\\s*/ ).pop()
                }, response );
            }"),
            'search' => new JsExpression('function() {
                // custom minLength
                var term = this.value.split( /,\s*/ ).pop()
                if ( term.length < 2 ) {
                    return false;
                }
            }'),
            'focus' => new JsExpression('function() {
                // prevent value inserted on focus
                return false;
            }'),
            'select' => new JsExpression('function( event, ui ) {
                var terms = this.value.split( /,\s*/ );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }'),
        ],
    ])->label(false) ?>
    <?php
    $this->registerJs('$("#project-tagvalues").bind("keydown", function( event ) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active ) {
                event.preventDefault();
            }
        })');
    ?>

    <?= $form->field($model, 'is_opensource')->checkbox() ?>
    <?= $form->field($model, 'source_url')
        ->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('source_url')])
        ->label(false) ?>

    <?php if (UserPermissions::canManageProjects()): ?>
        <?= $form->field($model, 'is_featured')->checkbox() ?>
    <?php endif ?>
</div>

<div class="description">
    <h2><?= $model->getAttributeLabel('description') ?></h2>

    <?= $form->field($model, 'description')->textarea(['class' => 'markdown-editor'])->label(false) ?>
</div>

<div class="team">
    <?php /* <h2>Team</h2> */ ?>
</div>

<div class="buttons-wrapper">
    <div class="next">
        <?= Html::submitButton(Yii::t('project', 'Next')) ?>
    </div>
</div>

<?php ActiveForm::end() ?>
