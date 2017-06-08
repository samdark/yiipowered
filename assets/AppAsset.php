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
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
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
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        MagnificPopupAsset::class,
        FontAwesomeAsset::class,
    ];
}
