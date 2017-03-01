<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use \yii\widgets\ListView;
use yii\bootstrap\Alert;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $filterForm \app\models\ProjectFilterForm */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects');
?>
<div class="row project-list">
    <div class="col-xs-2">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['project/list'],
        ]) ?>

        <?= $form->field($filterForm, 'title') ?>
        <?= $form->field($filterForm, 'url') ?>
        <?= $form->field($filterForm, 'opensource')->dropDownList($filterForm->getOpenSourceOptions(), ['prompt' => Yii::t('project', 'Does not matter')]) ?>
        <?= $form->field($filterForm, 'featured')->checkbox() ?>
        <?= $form->field($filterForm, 'yiiVersion')->dropDownList(\app\models\Project::versions(), ['prompt' => Yii::t('project', 'Any Verison')]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('project', 'Apply'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
    <div class="col-xs-10">
        <?php if (Yii::$app->session->hasFlash('project.project_successfully_added')) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-success',
                ],
                'body' => Yii::t('project', 'Project added!'),
            ]);
        } ?>

        <div class="container">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}{pager}',
                'options' => ['class' => 'projects-flow'],
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_card'
            ]) ?>
        </div>
    </div>
</div>