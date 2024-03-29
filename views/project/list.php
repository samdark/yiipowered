<?php

use app\models\Project;
use app\models\Tag;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use \yii\widgets\ListView;
use yii\bootstrap\Alert;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $tagsDataProvider yii\data\ActiveDataProvider */
/* @var $filterForm \app\models\ProjectFilterForm */
/* @var $this yii\web\View */
$this->title = Yii::t('project', 'Projects');
?>
<div class="projects-list">
    <div class="projects">
        <div class="filters-wrapper">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['project/list'],
            ]) ?>

            <div class="filters">
                <div class="title"><?= Yii::t('project', 'Filters') ?></div>

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
                        'prompt' => Yii::t('project', 'Any code access'),
                    ])
                    ->label(false)
                ?>
                <?= $form->field($filterForm, 'featured')->checkbox() ?>
                <?= $form->field($filterForm, 'verified')->checkbox() ?>
                <?= $form->field($filterForm, 'yiiVersion')
                    ->dropDownList(Project::versions(), [
                        'prompt' => Yii::t('project', 'Any Yii version'),
                    ])->label(false) ?>

                <?php if (\app\components\UserPermissions::canManageProjects()): ?>
                    <?= $form->field($filterForm, 'status')
                        ->dropDownList(Project::getAvailableStatuses(), [
                            'prompt' => Yii::t('project', 'Any Status'),
                        ])->label(false);
                    ?>

                    <?= $form->field($filterForm, 'notVerified')->checkbox() ?>
                <?php endif ?>

                <div class="form-group text-center">
                    <?= Html::submitButton(Yii::t('project', 'Apply'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="tags">
                <div class="title"><?= Yii::t('project', 'Tags') ?></div>

                <?= ListView::widget([
                    'dataProvider' => $tagsDataProvider,
                    'layout' => '{items}',
                    'options' => ['class' => 'list'],
                    'itemOptions' => ['class' => 'item'],
                    'itemView' => function ($model) use ($filterForm) {
                        /** @var Tag $model */
                        return Html::a(
                            '<span class="name">' . Html::encode($model->name) . '</span>' .
                            '<span class="count">' . $model->frequency . '</span>',
                            ['/project/list', 'tags' => $model->name],
                            ['class' => $filterForm->hasTag($model->name) ? 'selected' : '']
                        );
                    }
                ]) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'project'],
            'itemView' => '_card',
        ]) ?>
    </div>
</div>
