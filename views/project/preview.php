<?php

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 */

use yii\helpers\Html;

$this->title = Yii::t('project', 'Add project');
?>

<div class="project-preview">
    <ol class="wizard-progress">
        <li class="is-complete" data-step="1">
            <?= Html::a(Yii::t('project', 'General info'), ['project/update', 'id' => $model->id]) ?>
        </li>
        <li class="is-complete" data-step="2">
            <?= Html::a(Yii::t('project', 'Screenshots'), ['project/screenshots', 'id' => $model->id]) ?>
        </li>
        <li class="is-active progress__last" data-step="3">
            <?= Yii::t('project', 'Preview & Approve') ?>
        </li>
    </ol>

    <?= $this->render('view', [
        'model' => $model,
        'management' => false,
    ]) ?>

    <div class="control-buttons">
        <div class="buttons-wrapper">
            <div class="back">
                <?= Html::a(Yii::t('project', 'Back'), ['/project/screenshots', 'id' => $model->id]) ?>
            </div>
            <div class="draft">
                <?= Html::a(Yii::t('project', 'Save as draft'), ['/project/draft', 'id' => $model->id], [
                    'data-method' => 'POST',
                ]) ?>
            </div>
            <div class="publish">
                <?= Html::a(Yii::t('project', 'Publish'), ['/project/publish', 'id' => $model->id], [
                    'data-method' => 'POST',
                ]) ?>
            </div>
        </div>
    </div>
</div>
