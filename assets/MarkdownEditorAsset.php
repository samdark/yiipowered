<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * MarkdownEditorAsset groups assets for markdown editor
 */
class MarkdownEditorAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/markdown';
    public $js = [
        'editor.js',
    ];
    public $depends = [
        JqueryAsset::class,
        CodeMirrorAsset::class,
        CodeMirrorButtonsAsset::class,
    ];
}
