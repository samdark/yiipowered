<?php

use app\components\UserPermissions;
use yii\helpers\Html;
use app\models\Project;
use app\widgets\Avatar;
use yii\helpers\Url;
use yii\helpers\Markdown;
use \yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $management bool */

// OpenGraph metatags
$this->registerMetaTag(['property' => 'og:title', 'content' => Html::encode($model->title)]);
$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'YiiPowered']);
$this->registerMetaTag(['property' => 'og:url', 'content' => Url::canonical()]);

$this->title = $model->title;

$canManageProject = UserPermissions::canManageProject($model);
?>
<section class="project-view">
    <header>
        <div class="title">
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
                <p class="url"><?= Html::a(Html::encode($model->url), $model->url) ?></p>
            <?php endif ?>
        </div>
        <div class="authors">
            <ul>
                <?php foreach ($model->users as $user): ?>
                    <li>
                        <?= Html::a(Avatar::widget(['user' => $user]) . ' @' . Html::encode($user->username),
                            ['user/view', 'id' => $user->id], ['class' => 'author']) ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </header>

    <div class="project-body">
        <div class="container">
            <div class="information">
                <?php if ($model->is_opensource): ?>
                    <p><?= Html::a(Yii::t('project', 'Source Code'), $model->source_url, ['target' => '_blank']) ?></p>
                <?php endif ?>

                <p><?= Yii::t('project', 'Yii Version') ?>: <?= Html::encode($model->yii_version) ?></p>

                <ul class="tags">
                    <?php foreach ($model->tags as $tag): ?>
                        <li><?= Html::a(Html::encode($tag->name), ['project/list', 'tags' => $tag->name]) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>

            <div class="details">
                <div class="images">
                    <?php if (empty($model->images)): ?>
                        <img class="image" src="<?= $model->getPlaceholderRelativeUrl() ?>" alt="">
                    <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($model->images as $image): ?>
                            <div class="image">
                                <a href="<?= $image->getUrl() ?>">
                                    <img class="img-responsive" src="<?= $i === 0 ? $image->getBigThumbnailRelativeUrl() : $image->getThumbnailRelativeUrl() ?>" alt="">
                                </a>
                            </div>
                            <?php $i++; ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>

                <div class="description">
                    <?= HtmlPurifier::process(Markdown::process($model->getDescription(), 'gfm')) ?>
                </div>

                <?php if ($management !== false) : ?>
                    <div class="management">
                        <?php if ($canManageProject): ?>
                            <div class="controls">
                                <?= Html::a(Yii::t('project', 'Update'), ['update', 'id' => $model->id],
                                    ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif ?>


                        <?php if ($canManageProject): ?>
                            <span class="time">
                            <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                                <?= Yii::$app->formatter->asDate($model->created_at) ?>
                        </span>

                            <span>
                            <?= Yii::t('project', 'Updated: ') ?>
                            <?= Yii::$app->formatter->asDate($model->updated_at) ?>
                        </span>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>
