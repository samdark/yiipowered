<?php
/* @var $model app\models\Project */
/* @var yii\web\View $this */

use app\widgets\Avatar;
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
    <div class="col-xs-12 col-md-4">
        <h1><?= Html::a(Html::encode($model->title), ['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?></h1>

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

        <p><?= Yii::t('project', 'Status') .": ". $model->getStatusLabel() ?></p>

        <div class="content">
            <?= HtmlPurifier::process(Markdown::process($model->getDescription(), 'gfm')) ?>
        </div>
    </div>
    <div class="col-xs-12 col-md-8">
        <div class="container">
            <?php if (empty($model->images)): ?>
                <div class="col-xs-4">
                    <img class="img-responsive" src="<?= $model->getPlaceholderUrl() ?>" alt="">
                </div>
            <?php else: ?>
                <?php foreach ($model->images as $image): ?>
                    <div class="col-xs-4">
                        <img class="img-responsive" src="<?= $image->getUrl() ?>" alt="">
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
</div>

