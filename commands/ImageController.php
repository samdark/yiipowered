<?php


namespace app\commands;


use app\models\Image;
use yii\console\Controller;
use yii\helpers\Console;

class ImageController extends Controller
{
    public function actionGenerateAll()
    {
        $batchSize = 100;
        $batch = Image::find()->batch($batchSize);
        foreach ($batch as $images) {
            $this->processImagesOneByOne($images);
        }
    }

    /**
     * @param Image[] $images
     */
    private function processImagesOneByOne(array $images)
    {
        foreach ($images as $i => $image) {
            $this->generateThumbs($image);
        }
    }

    public function actionGenerate($id)
    {
        $this->generateThumbs(Image::findOne(['id' => $id]));
    }

    public function actionGenerateForProject($id)
    {
        $images = Image::find()->where(['project_id' => $id])->all();
        foreach ($images as $image) {
            /* @var Image $image */
            $this->generateThumbs($image);
        }
    }

    protected function generateThumbs(Image $image)
    {
        echo 'Generating image #' . $image->id . ' for project #' . $image->project_id;

        $image->generateFull();
        $image->generateThumbnail();
        $image->generateBigThumbnail();

        echo Console::renderColoredString(" %Gdone.%n\n");
    }
}
