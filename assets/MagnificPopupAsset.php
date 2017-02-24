<?php

namespace app\assets;

use yii\web\AssetBundle;

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
        'yii\web\JqueryAsset',
    ];
}
