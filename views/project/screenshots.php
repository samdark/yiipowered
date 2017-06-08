<?php

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 * @var $imageUploadForm \app\models\ImageUploadForm
 * @var Image[] $images
 */

use app\assets\ImageCropperAsset;
use app\models\Image;
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
            <?= Html::a(Yii::t('project', 'General info'), ['project/update', 'id' => $model->id]) ?>
        </li>
        <li class="is-active" data-step="2">
            <?= Yii::t('project', 'Screenshots') ?>
        </li>
        <li data-step="3" class="progress__last">
            <?= Html::a(Yii::t('project', 'Preview & Approve'), ['project/preview', 'id' => $model->id]) ?>
        </li>
    </ol>

    <div class="form-box">
        <div class="screenshots-wrapper">
            <div class="images">
                <?php if (empty($images) && !isset($imageUploadForm)): ?>
                    <img class="image" src="<?= $model->getPlaceholderRelativeUrl() ?>" alt="">
                <?php else: ?>
                    <?php $i = 0; ?>
                    <?php foreach ($images as $image): ?>
                        <div class="image">
                            <a href="<?= $image->getUrl() ?>">
                                <img class="img-responsive"
                                     src="<?= $i === 0 ? $image->getBigThumbnailRelativeUrl() : $image->getThumbnailRelativeUrl() ?>"/>
                            </a>
                            <span class="recrop js-project-image-recrop"
                                  data-id="<?= $image->id ?>"
                                  data-url="<?= Url::to(['project/image-original', 'imageId' => $image->id]) ?>"
                                  title="<?= Yii::t('project', 'Re-crop image') ?>">
                                <span class="fa fa-scissors"></span>
                            </span>
                            <span class="delete" data-id="<?= $image->id ?>"
                                  data-url="<?= Url::to(['project/delete-image']) ?>"
                                  data-confirm="<?= Yii::t('project', 'Are you sure you want to delete this image?') ?>"
                                  title="<?= Yii::t('project', 'Delete image') ?>">
                                <span class="fa-stack fa-lg">
                                  <i class="fa fa-circle fa-stack-2x"></i>
                                  <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                </span>
                            </span>
                            <span class="primary-image <?= $model->primary_image_id == $image->id ? 'hide' : '' ?>"
                                  data-image-id="<?= $image->id ?>"
                                  data-url="<?= Url::to(["api/1.0/projects/{$image->project->id}/primary-image"]) ?>"
                                  title="<?= Yii::t('project', 'Make as primary image') ?>">
                                
                                <span class="fa-stack fa-lg">
                                  <i class="fa fa-circle fa-stack-2x"></i>
                                  <i class="fa fa-home fa-stack-1x fa-inverse"></i>
                                </span>
                            </span>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach ?>

                    <?php if (isset($imageUploadForm)): ?>
                        <?php $form = ActiveForm::begin(['id' => 'project-image-upload', 'options' => ['class' => 'image upload']]) ?>
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

            <div class="docs">
                <p><?= Yii::t('project', 'Please, upload some screenshots to make your project look nice.') ?></p>
                <p><?= Yii::t('project', 'You can upload up to 5 images. The first image will be used as a main preview of your project, while others will be displayed on the project details page') ?></p>
                <p><?= Yii::t('project', 'After the image upload, you will be able to crop it.') ?></p>
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
