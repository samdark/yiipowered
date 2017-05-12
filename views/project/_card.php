<?php
/**
 * @var $model \app\models\Project
 */

use app\widgets\bookmark\Bookmark;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<article class="<?= $model->getStatusClass() ?>">
    <a class="details" href="<?= Url::to(['project/view', 'id' => $model->id, 'slug' => $model->slug]) ?>">
        <img class="img-responsive" src="<?= $model->getPrimaryImageThumbnailRelativeUrl() ?>" />
        <h1><?= Html::encode($model->title) ?></h1>
    </a>

    <p class="tags">
        <?= implode(', ', array_map(function ($tag) {
            return Html::a(Html::encode($tag->name), ['/project/list', 'tags' => $tag->name]);
        }, array_slice($model->tags, 0, 10))); ?>
    </p>

    <?= Bookmark::widget(['project' => $model]) ?>
</article>
