<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\bootstrap\BootstrapAsset;
use yii\web\YiiAsset;

/**
 * Mail application asset
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/app';

    public $css = [
        'styles/main.less',
    ];
    public $js = [
        'js/main.js',
        'js/bookmark.js',
        'js/vote.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        MagnificPopupAsset::class,
        FontAwesomeAsset::class,
    ];
}
