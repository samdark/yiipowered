<?php

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 * @var $imageUploadForm \app\models\ImageUploadForm
 */

use app\assets\ImageCropperAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('project', 'Upload screenshots');

ImageCropperAsset::register($this);
$sizeThumb = Yii::$app->params['image.size.thumbnail'];
$this->registerJs("initProjectImageUpload({$sizeThumb[0]}, {$sizeThumb[1]});");
?>

<div class="project-screenshots">
    <ol class="wizard-progress">
        <li class="is-complete" data-step="1">
            <?= Yii::t('project', 'General info') ?>
        </li>
        <li class="is-active" data-step="2">
            <?= Yii::t('project', 'Screenshots') ?>
        </li>
        <li data-step="3" class="progress__last">
            <?= Yii::t('project', 'Approve') ?>
        </li>
    </ol>

    <div class="form-box">
        <div class="screenshots-wrapper">
            <div class="images">
                <?php if (empty($model->images) && !isset($imageUploadForm)): ?>
                    <img class="image" src="<?= $model->getPlaceholderRelativeUrl() ?>" alt="">
                <?php else: ?>
                    <?php $i = 0; ?>
                    <?php foreach ($model->images as $image): ?>
                        <div class="image">
                            <a href="<?= $image->getUrl() ?>">
                                <img class="img-responsive"
                                     src="<?= $i === 0 ? $image->getBigThumbnailRelativeUrl() : $image->getThumbnailRelativeUrl() ?>"/>
                            </a>
                            <?php if ($canManageProject): ?>
                                <span class="recrop glyphicon glyphicon-scissors js-project-image-recrop"
                                      data-id="<?= $image->id ?>"
                                      data-url="<?= Url::to(['project/image-original', 'imageId' => $image->id]) ?>"
                                      title="<?= Yii::t('project', 'Re-crop image') ?>"></span>
                                <span class="delete glyphicon glyphicon-remove" data-id="<?= $image->id ?>"
                                      data-url="<?= Url::to(['project/delete-image']) ?>"
                                      data-confirm="<?= Yii::t('project',
                                          'Are you sure you want to delete this image?') ?>"
                                      title="<?= Yii::t('project', 'Delete image') ?>"></span>
                            <?php endif ?>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach ?>

                    <?php if (isset($imageUploadForm)): ?>
                        <?php $form = ActiveForm::begin(['id' => 'project-image-upload', 'options' => ['class' => "image upload"]]) ?>
                        <?= $form->errorSummary($imageUploadForm) ?>

                        <?= Html::activeHiddenInput($imageUploadForm, 'imageCropData') ?>
                        <?= Html::activeHiddenInput($imageUploadForm, 'imageId') ?>

                        <label for="upload-button" class="custom-upload-button">
                        <span class="fa-stack fa-4x">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                        </span>
                        </label>
                        <?= $form->field($imageUploadForm, 'file', ['options' => ['class' => '']])
                            ->fileInput([
                                'accept' => 'image/png',
                                'id' => 'upload-button',
                                'class' => 'upload-button',
                            ])
                            ->label(false)
                        ?>

                        <div style="display: none;" class="cropper-block">
                            <p>
                                <?= Html::submitButton(Yii::t('project', 'Upload'), [
                                    'class' => 'btn btn-default btn-success',
                                ]) ?>
                                <?= Html::button(Yii::t('project', 'Cancel'), [
                                    'class' => 'btn btn-danger js-project-image-reset',
                                ]) ?>
                            </p>

                            <img class="image-block" src="" style="max-height: 500px">
                        </div>

                        <?php ActiveForm::end() ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>

        <div class="control-buttons">
            <div class="buttons-wrapper">
                <div class="back">
                    <?= Html::a(Yii::t('project', 'Back'), ['/project/update', 'id' => $model->id]) ?>
                </div>
                <div class="next">
                    <?= Html::a(Yii::t('project', 'Next'), ['/project/preview', 'id' => $model->id]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
