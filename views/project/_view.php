<?php
/* @var $model app\models\Project */
/* @var yii\web\View $this */
/* @var \app\models\ImageUploadForm $imageUploadForm */

use app\widgets\Avatar;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Markdown;
use \yii\helpers\HtmlPurifier;

// OpenGraph metatags
$this->registerMetaTag(['property' => 'og:title', 'content' => Html::encode($model->title)]);
$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'YiiPowered']);
$this->registerMetaTag(['property' => 'og:url', 'content' => Url::canonical()]);

?>

<div class="row">
    <div class="col-xs-12">
        <h1>
            <?= Html::encode($model->title) ?>
            <?php if ($model->is_featured): ?>
                <span class="glyphicon glyphicon-star featured" aria-hidden="true"></span>
            <?php endif ?>
        </h1>

        <?php if (!empty($model->url)): ?>
            <p><?= Html::a(Html::encode($model->url), $model->url) ?></p>
        <?php endif ?>

        <p class="time">
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
            <?= Yii::$app->formatter->asDate($model->created_at) ?>
        </p>

        <?php foreach ($model->users as $user): ?>
            <p class="author">
                <?= Html::a(Avatar::widget(['user' => $user]) . ' @' . Html::encode($user->username), ['user/view', 'id' => $user->id]) ?>
            </p>
        <?php endforeach ?>

        <?php if ($model->is_opensource): ?>
            <?= Yii::t('project', 'Source Code: ') . Html::a($model->source_url, $model->source_url) ?>
        <?php endif ?>

        <?php if (\app\components\UserPermissions::canManageProject($model)): ?>
            <p><?= Yii::t('project', 'Status: ') . $model->getStatusLabel() ?></p>
            <p><?= Yii::t('project', 'Updated: ') . Yii::$app->formatter->asDate($model->updated_at) ?></p>
        <?php endif ?>

        <div class="content">
            <?= HtmlPurifier::process(Markdown::process($model->getDescription(), 'gfm')) ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="container">
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
                <?php if ($imageUploadForm !== null): ?>
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
</div>
