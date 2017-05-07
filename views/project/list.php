<?php

use app\models\Project;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use \yii\widgets\ListView;
use yii\bootstrap\Alert;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $filterForm \app\models\ProjectFilterForm */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects');
?>
<div class="projects-list">
    <div class="projects">
        <div class="filters">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['project/list'],
            ]) ?>

            <?= $form->field($filterForm, 'title')
                ->textInput(['placeholder' => $filterForm->getAttributeLabel('title')])
                ->label(false)
            ?>
            <?= $form->field($filterForm, 'url')
                ->textInput(['placeholder' => $filterForm->getAttributeLabel('url')])
                ->label(false)
            ?>
            <?= $form->field($filterForm, 'opensource')
                ->dropDownList($filterForm->getOpenSourceOptions(), [
                    'prompt' => Yii::t('project', 'Any code access')
                ])
                ->label(false)
            ?>
            <?= $form->field($filterForm, 'featured')->checkbox() ?>
            <?= $form->field($filterForm, 'yiiVersion')
                ->dropDownList(Project::versions(), [
                    'prompt' => Yii::t('project', 'Any Yii verison')
                ])->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('project', 'Apply'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>

        <?php if (Yii::$app->session->hasFlash('project.project_successfully_added')) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-success',
                ],
                'body' => Yii::t('project', 'Project added!'),
            ]);
        } ?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_card'
        ]) ?>
    </div>
</div>
