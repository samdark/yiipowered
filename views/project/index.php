<?php

use yii\helpers\Html;
use \yii\widgets\ListView;

/* @var $featuredProvider yii\data\ActiveDataProvider */
/* @var $newProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
/* @var $projectsCount int */
/* @var $seeMoreCount int */

$this->title = Yii::t('project', 'Projects built with Yii');
?>
<div class="intro">
    <p>
        <?= Yii::t('project', '{n, plural, one{# project} other{# projects}} made with {link}', [
            'n' => $projectsCount,
            'link' => Html::a('Yii framework', 'http://yiiframework.com'),
        ]) ?>
    </p>
    <p class="add-project-wrapper">
        <?= Html::a(Yii::t('project', 'Made one? Share it!'), ['project/create']) ?>
    </p>
</div>

<div class="project-index">
    <section class="group">
        <header><?= Yii::t('project', 'Featured projects') ?></header>

        <?= ListView::widget([
            'dataProvider' => $featuredProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'project'],
            'itemView' => '_card',
        ]) ?>
    </section>

    <section class="group">
        <header><?= Yii::t('project', 'New projects') ?></header>

        <?= ListView::widget([
            'dataProvider' => $newProvider,
            'layout' => '{items}',
            'options' => ['class' => 'projects-flow'],
            'itemOptions' => ['class' => 'project'],
            'itemView' => '_card',
        ]) ?>

        <p class="show-more">
            <?= Html::a(Yii::t('project', 'View other {n, plural, one{# project} other{# projects}}', [
                'n' => $seeMoreCount
            ]), ['project/list']) ?>
        </p>
    </section>

</div>
