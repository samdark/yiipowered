<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $imageUploadForm \app\models\ImageUploadForm */

$this->title = $model->title;
?>
<div class="row news-view">

    <div class="col-xs-12">
        <?php if(\Yii::$app->user->can('manage_projects')): ?>
            <div class="controls">
                <?= Html::a(Yii::t('project', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('project', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('project', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>

                <?php if ($imageUploadForm !== null): ?>
                    <?php $form = ActiveForm::begin(['id' => 'project-image-upload']) ?>
                        <?= $form->field($imageUploadForm, 'files')->fileInput(['multiple' => true, 'accept' => 'image/png']) ?>
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('project', 'Upload'), ['class' => 'btn btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end() ?>
                <?php endif ?>
            </div>
        <?php endif ?>
        <?= $this->render('_view', [
            'model' => $model,
        ]) ?>
    </div>
</div>