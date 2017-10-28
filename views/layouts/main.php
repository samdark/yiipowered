<?php

use app\helpers\GoogleAnalytics;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - YiiPowered</title>
    <link rel="alternate" type="application/rss+xml" title="YiiPowered" href="<?= \yii\helpers\Url::to(['project/rss'], true)?>"/>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <div class="content">
            <?php
            NavBar::begin([
                'brandLabel' => '<span class="yii-logo"></span> YiiPowered',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            $menuItems = [
                [
                    'label' => Html::tag('span', '<span> ' . Yii::t('app', 'Explore projects'). '</span>', ['class' => 'fa fa-search']),
                    'encode' => false,
                    'url' => ['/projects'],
                    'linkOptions' => ['alt' => Yii::t('app', 'Explore projects'), 'title' => Yii::t('app', 'Explore projects')],
                ],
                [
                    'label' => Html::tag('span', '<span> ' . Yii::t('app', 'RSS feed') . '</span>', ['class' => 'fa fa-rss-square']),
                    'encode' => false,
                    'url' => ['/project/rss'],
                    'linkOptions' => [
                        'alt' => Yii::t('app', 'RSS feed'),
                        'title' => Yii::t('app', 'RSS feed'),
                    ]
                ],
                [
                    'label' => Yii::t('project', 'Top {n}', ['n' => Yii::$app->params['project.maxTopProjects']]),
                    'url' => ['/project/top-projects'],
                ]
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = [
                    'label' => Html::tag('span', '<span> ' . Yii::t('app', 'Login') . '</span>', ['class' => 'fa fa-sign-in']),
                    'encode' => false,
                    'url' => ['/site/login'],
                    'linkOptions' => ['alt' => Yii::t('app', 'Login'), 'title' => Yii::t('app', 'Login')],
                ];
            } else {
                $menuItems[] = [
                    'label' => Yii::t('user', 'Manage users'),
                    'url' => ['/user/index'],
                    'visible'=> \Yii::$app->user->can('manage_users'),
                ];
                $menuItems[] = [
                    'label' => Html::tag('span', '<span> ' . Yii::t('app', 'Bookmarks') . '</span>', ['class' => 'fa fa-bookmark']),
                    'encode' => false,
                    'url' => ['/project/bookmarks'],
                    'linkOptions' => ['alt' => Yii::t('app', 'Bookmarks'), 'title' => Yii::t('app', 'Bookmarks')],
                ];
                $menuItems[] = ['label' => Yii::$app->user->identity->username, 'url' => ['/user/view', 'id' => \Yii::$app->user->id]];
                $menuItems[] = [
                    'label' => Html::tag('span', '<span> ' . Yii::t('app', 'Logout') . '</span>', ['class' => 'fa fa-sign-out']),
                    'url' => ['/site/logout'],
                    'encode' => false,
                    'linkOptions' => [
                        'data-method' => 'post',
                        'alt' => Yii::t('app', 'Logout'),
                        'title' => Yii::t('app', 'Logout'),
                    ],
                ];
            }
            ?>

            <ul class="navbar-nav navbar-right nav">
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="fa fa-globe"></span> <b class="caret"></b></a>
                    <?= \app\widgets\LanguageDropdown::widget() ?>
                </li>
            </ul>

            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]); ?>

            <ul class="navbar-nav navbar-right nav">
                <li>
                    <?= yii\helpers\Html::a(Yii::t('project', 'Add project'), ['project/create'], ['class' => 'btn-add-project']) ?>
                </li>
            </ul>

            <?php NavBar::end(); ?>

            <div class="content-wrapper">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="left">
                    &copy; YiiPowered <?= date('Y') ?> |
                    <a href="https://twitter.com/yiipowered">Twitter</a> |
                    <?= Html::a(Yii::t('app', 'About'), ['/site/about']) ?>
                </div>

                <div class="right">
                    <?= Yii::powered() ?>
                </div>
            </div>
        </footer>
    </div>

    <?php GoogleAnalytics::track('UA-96041959-1') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
