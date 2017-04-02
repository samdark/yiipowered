<?php
/**
 * @var \yii\web\View $this
 * @var integer $starValue
 * @var integer $starCount
 * @var string $ajaxUrl
 */

use yii\bootstrap\Html;

?>

<div class="star-wrapper">
    <?= Html::tag('span', 
        Html::icon($starValue ? 'star' : 'star-empty', ['class' => 'icon']), 
        ['data-star-url' => $ajaxUrl]
    ) ?>   
    
    <span class="star-count"><?= Html::encode($starCount) ?></span> - <?= Yii::t('star', 'stars') ?>
</div>
