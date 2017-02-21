<?php

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
?>
<div class="news-view">

    <div class="row">
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

                <?php if ($model->status !== Project::STATUS_PUBLISHED && \app\components\UserPermissions::canManageProject($model)): ?>
                    <span class="label <?= $model->getStatusClass() ?>"><?= $model->getStatusLabel() ?></span>
                <?php endif ?>
            </h1>

            <?php if (!empty($model->url)): ?>
                <p><?= Html::a(Html::encode($model->url), $model->url) ?></p>
            <?php endif ?>

            <?php if ($model->is_opensource): ?>
                <?= Yii::t('project', 'Source Code: ') . Html::a($model->source_url, $model->source_url) ?>
            <?php endif ?>
        </div>
        <div class="col-xs-12">

                <?php if (empty($model->images)): ?>
                    <div class="col-xs-4">
                        <img class="img-responsive" src="<?= $model->getPlaceholderUrl() ?>" alt="">
                    </div>
                <?php else: ?>
                    <?php foreach ($model->images as $image): ?>
                        <div class="col-xs-4">
                            <a href="<?= $image->getUrl() ?>"><img class="img-responsive" src="<?= $image->getThumbnailUrl() ?>" alt=""></a>
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
    </div>

    <div class="col-xs-12">
        <div class="content">
            <?= HtmlPurifier::process(Markdown::process($model->getDescription(), 'gfm')) ?>
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
        <div class="col-xs-6">
            <ul class="authors">
            <?php foreach ($model->users as $user): ?>
                <li>
                    <?= Html::a(Avatar::widget(['user' => $user]) . ' @' . Html::encode($user->username), ['user/view', 'id' => $user->id], ['class' => 'author']) ?>
                </li>
            <?php endforeach ?>
            </ul>
        </div>

        <div class="col-xs-6 pull-right">
            <?php if (\app\components\UserPermissions::canManageProject($model)): ?>
                <span class="time">
                    <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                    <?= Yii::$app->formatter->asDate($model->created_at) ?>
                </span>

                <span><?= Yii::t('project', 'Updated: ') . Yii::$app->formatter->asDate($model->updated_at) ?></span>
            <?php endif ?>
        </div>
    </div>
</div>