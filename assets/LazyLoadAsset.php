<?php


namespace app\assets;


use yii\web\AssetBundle;

/**
 * CodeMirrorAsset groups assets for code editing areas
 */
class LazyLoadAsset extends AssetBundle
{
    public $sourcePath = null;

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.5.2/lazyload.min.js',
    ];
}
