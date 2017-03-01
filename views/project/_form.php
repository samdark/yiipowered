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

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <fieldset class="well">
                <h2><?= Yii::t('project', 'General') ?></h2>

                <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
                <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
                <?= $form->field($model, 'yii_version')->dropDownList(Project::versions()) ?>


                <?= $form->field($model, 'tagValues')->widget(\yii\jui\AutoComplete::classname(), [
                    'options' => [
                        'class' => 'form-control',
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
                        }') ,
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
                        }')
                    ],
                ]) ?>
                <?php
                $this->registerJs('$("#project-tagvalues").bind("keydown", function( event ) {
                    if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active ) {
                        event.preventDefault();
                    }
                })');
                ?>
            </fieldset>
        </div>

        <div class="col-xs-12 col-sm-6">
            <fieldset class="well">
                <h2><?= Yii::t('project', 'Source') ?></h2>

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