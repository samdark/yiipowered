<?php

use app\components\UserPermissions;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\Project;
use app\widgets\Avatar;
use yii\helpers\Url;
use yii\helpers\Markdown;
use \yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $imageUploadForm \app\models\ImageUploadForm */

// OpenGraph metatags
$this->registerMetaTag(['property' => 'og:title', 'content' => Html::encode($model->title)]);
$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'YiiPowered']);
$this->registerMetaTag(['property' => 'og:url', 'content' => Url::canonical()]);

$this->title = $model->title;

$canManageProject = UserPermissions::canManageProject($model);
?>
<div class="project-view">

    <div class="row">
        <div class="col-xs-12">
            <?php if ($canManageProject): ?>
                <div class="controls">
                    <?= Html::a(Yii::t('project', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h1>
                <?= Html::encode($model->title) ?>
                <?php if ($model->is_featured): ?>
                    <span class="glyphicon glyphicon-star featured" aria-hidden="true"></span>
                <?php endif ?>

                <?php if ($model->status !== Project::STATUS_PUBLISHED && $canManageProject): ?>
                    <span class="label <?= $model->getStatusClass() ?>"><?= $model->getStatusLabel() ?></span>
                <?php endif ?>
            </h1>

            <?php if (!empty($model->url)): ?>
                <p><?= Html::a(Html::encode($model->url), $model->url) ?></p>
            <?php endif ?>

            <?php if ($model->is_opensource): ?>
                <p><?= Yii::t('project', 'Source Code: ') . Html::a($model->source_url, $model->source_url) ?></p>
            <?php endif ?>

            <p><?= Yii::t('project', 'Yii Version') ?>: <?= Html::encode($model->yii_version) ?></p>
        </div>
    </div>

    <div class="row images">
        <?php if (empty($model->images)): ?>
            <div class="col-xs-4">
                <div class="image">
                    <img class="img-responsive" src="<?= $model->getPlaceholderUrl() ?>" alt="">
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($model->images as $image): ?>
                <div class="col-xs-4">
                    <div class="image">
                        <a href="<?= $image->getUrl() ?>"><img class="img-responsive" src="<?= $image->getThumbnailUrl() ?>" alt=""></a>
                        <?php if ($canManageProject): ?>
                            <span class="delete glyphicon glyphicon-remove" data-id="<?= $image->id ?>" data-url="<?= Url::to(['project/delete-image']) ?>" data-confirm="<?= Yii::t('project', 'Are you sure you want to delete this image?') ?>"></span>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
        <div class="col-xs-4">
            <?php if (isset($imageUploadForm)): ?>
                <?php $form = ActiveForm::begin(['id' => 'project-image-upload']) ?>
                    <?= $form->field($imageUploadForm, 'files')->fileInput(['multiple' => true, 'accept' => 'image/png']) ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('project', 'Upload'), ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end() ?>
            <?php endif ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="content">
                <?= HtmlPurifier::process(Markdown::process($model->getDescription(), 'gfm')) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <ul class="tags">
                <?php foreach ($model->tags as $tag): ?>
                    <li><?= Html::encode($tag->name) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <ul class="authors">
            <?php foreach ($model->users as $user): ?>
                <li>
                    <?= Html::a(Avatar::widget(['user' => $user]) . ' @' . Html::encode($user->username), ['user/view', 'id' => $user->id], ['class' => 'author']) ?>
                </li>
            <?php endforeach ?>
            </ul>
        </div>

        <div class="col-xs-12">
            <?php if ($canManageProject): ?>
                <span class="time">
                    <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                    <?= Yii::$app->formatter->asDate($model->created_at) ?>
                </span>

                <span><?= Yii::t('project', 'Updated: ') . Yii::$app->formatter->asDate($model->updated_at) ?></span>
            <?php endif ?>
        </div>
    </div>
</div>