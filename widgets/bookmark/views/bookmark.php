<?php
/**
 * @var \yii\web\View $this
 * @var string $ajaxUrl
 * @var bool $bookmarkExists
 */

use yii\bootstrap\Html;

?>

<div class="bookmark-wrapper">
    <span class="action" data-bookmark-url="<?= Html::encode($ajaxUrl) ?>" data-bookmark-exists="<?= (int) $bookmarkExists ?>">
        <?= Html::icon('bookmark') ?>
        <span class="action-item <?= $bookmarkExists ? 'hide' : '' ?>"><?= Yii::t('bookmark', 'Add to bookmarks')?></span>
        <span class="action-item <?= $bookmarkExists ? '' : 'hide' ?>"><?= Yii::t('bookmark', 'Remove from bookmarks')?></span>
    </span>
</div>
