<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ImageCropperAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
        'cropper/dist/cropper.min.css',
    ];
    
    public $js = [
        'cropper/dist/cropper.min.js',
    ];
    
    public $depends = [
        JqueryAsset::class,
    ];
}
