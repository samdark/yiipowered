<?php


namespace app\assets;


use yii\web\AssetBundle;

/**
 * LazyLoadAsset groups assets for Image Lazy Load
 */
class LazyLoadAsset extends AssetBundle
{
    public $sourcePath = '@bower/vanilla-lazyload';

    public $js = [
        'dist/lazyload.js',
    ];
}
