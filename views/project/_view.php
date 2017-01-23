<?php
/* @var $model app\models\Project */

use app\widgets\Avatar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Markdown;
use \yii\helpers\HtmlPurifier;

/* @var yii\web\View $this */

$isFull = isset($isFull) ? $isFull : false;
$displayStatus = isset($displayStatus) ? $displayStatus : false;
$displayUser = isset($displayUser) ? $displayUser : true;
$displayModeratorButtons = isset($displayModeratorButtons) ? $displayModeratorButtons : false;

// OpenGraph metatags
$this->registerMetaTag(['property' => 'og:title', 'content' => Html::encode($model->title)]);
$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'YiiPowered']);
$this->registerMetaTag(['property' => 'og:url', 'content' => Url::canonical()]);

?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&appId=444774969003761&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="row">
    <div class="col-md-2 col-sm-3 post-meta">
        <p class="time">
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
            <?= Yii::$app->formatter->asDate($model->created_at) ?>
        </p>
        <?php if ($displayUser): ?>
            <?php foreach ($model->users as $user): ?>
                <p class="author">
                    <?= Html::a(Avatar::widget(['user' => $user]) . ' @' . Html::encode($user->username), ['user/view', 'id' => $user->id]) ?>
                </p>
            <?php endforeach ?>
        <?php endif ?>

        <?php if ($displayStatus): ?>
        <p><?= Yii::t('project', 'Status') .": ". $model->getStatusLabel() ?></p>
        <?php endif ?>

        <?= Html::a(Yii::t('project', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('project', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('project', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="col-sm-9 col-md-10 post">
        <h1>
            <?= $isFull ? Html::encode($model->title) : Html::a(Html::encode($model->title), ['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?>
        </h1>

        <div class="content">
            <?= HtmlPurifier::process(Markdown::process($model->getDescription(), 'gfm')) ?>

            <?php if ($isFull): ?>
            <div class="meta">
                <?php if (!empty($model->link)): ?>
                    <p><?= Html::a(Html::encode($model->link), $model->link) ?></p>
                <?php endif ?>

                <a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-hashtags="yii" data-url="<?= Url::canonical() ?>" data-text="<?= Html::encode($model->title) ?>">Tweet</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

                <div class="fb-share-button" data-href="<?= Url::canonical() ?>" data-layout="button"></div>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>
