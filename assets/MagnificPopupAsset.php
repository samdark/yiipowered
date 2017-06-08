<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class MagnificPopupAsset extends AssetBundle
{
    public $sourcePath = '@bower/magnific-popup/dist';
    public $css = [
        'magnific-popup.css',
    ];
    public $js = [
        'jquery.magnific-popup.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
