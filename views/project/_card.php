<?php
/**
 * @var $model \app\models\Project
 */

use app\widgets\bookmark\Bookmark;
use app\widgets\Vote;
use yii\helpers\Html;
use yii\helpers\Url;

$bgImg = Yii::$app->request->baseUrl . '/img/project_no_image.png';
?>

<article class="<?= $model->getStatusClass() ?>">
    <a class="details" href="<?= Url::to(['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?>">
        <img class="img-responsive lazy" src="<?=$bgImg?>" data-src="<?= $model->getPrimaryImageThumbnailRelativeUrl() ?>" />
        <h1><?= Html::encode($model->title) ?></h1>
    </a>

    <p class="tags">
        <?= implode(', ', array_map(function ($tag) {
            return Html::a(Html::encode($tag->name), ['/project/list', 'tags' => $tag->name]);
        }, array_slice($model->tags, 0, 10))); ?>
    </p>

    <?= Bookmark::widget(['project' => $model]) ?>
    <?= Vote::widget(['project' => $model]) ?>
</article>
