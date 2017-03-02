<?php

namespace app\assets;

use yii\web\AssetBundle;

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
        'yii\web\JqueryAsset',
    ];
}
