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
            $childPids = [];

            foreach ($images as $i => $image) {
                /** @var Image $image */
                $newPid = pcntl_fork();
                if ($newPid == -1) {
                    die('Can\'t fork');
                } elseif ($newPid) {
                    $childPids[] = $newPid;
                } else {
                    \Yii::$app->db->pdo = null;
                    $this->generateThumbs($image);
                    exit(0);
                }
            }

            foreach ($childPids as $childPid) {
                pcntl_waitpid($childPid, $status);
            }
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
        $image->generateFull();
        $image->generateThumbnail();
        $image->generateBigThumbnail();

        echo Console::renderColoredString('Generating thumb image ' . $image->id . ' for project ' . $image->project_id . " %Gdone.%n\n");
    }
}
