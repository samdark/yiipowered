<?php


namespace app\commands;


use app\models\Image;
use yii\console\Controller;

class ImageController extends Controller
{
    public function actionProcess()
    {
        foreach (Image::find()->each() as $image) {
            /** @var Image $image */
            $image->generateFull();
            $image->generateThumbnail();
        }
    }
}