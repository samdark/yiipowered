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
            'link' => Html::a('Yii framework', 'https://www.yiiframework.com'),
        ]) ?>
    </p>

    <?= Html::a(
        Yii::t('project', 'Made one? Share it!'),
        ['project/create'],
        ['class' => 'add-project']
    ) ?>
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


        <div class="show-more">
            <?= Html::a(
                Yii::t('project', 'View  {n, plural, one{one more project} other{# more projects}}', [
                'n' => $seeMoreCount,
                ]),
                ['project/list', 'page' => 2]
            ) ?>
        </div>

    </section>

</div>
