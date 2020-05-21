<?php


namespace app\commands;


use app\models\Image;
use app\models\Project;
use yii\base\ErrorException;
use yii\console\Controller;
use yii\helpers\Console;

class ImageController extends Controller
{
    public function actionFetch()
    {
        $projectsWithoutImages = $this->getProjectsWithoutImages();

        foreach ($projectsWithoutImages as $projectWithoutImages) {
            echo $projectWithoutImages->id . '/' . $projectWithoutImages->slug . ' ' . $projectWithoutImages->url . "\n";
            $this->fetchImage($projectWithoutImages);
            echo "\n";
        }
    }

    private function fetchImage(Project $project)
    {
        if (filter_var($project->url, FILTER_VALIDATE_URL) === false) {
            echo "Invalid URL.\n";
            return;
        }

        //$imageUrl = \Yii::$app->screenshotLayer->getScreenshotUrl($project->url);
        $imageUrl = \Yii::$app->apiFlash->getScreenshotUrl($project->url);

        $image = new Image();
        $image->project_id = $project->id;

        if (!$image->save()) {
            echo "Unable to save image.\n";
            return;
        }

        echo "Fetching image.\n";
        try {
            $data = file_get_contents($imageUrl);
        } catch (ErrorException $e) {
            echo $e . "\n";
            return;
        }

        echo "Writing image.\n";
        file_put_contents($image->ensureOriginalPath(), $data);

        echo "Generating...\n";
        $image->generateFull();
        $image->generateThumbnail();
        $image->generateBigThumbnail();
    }

    /**
     * @return Project[]
     */
    private function getProjectsWithoutImages()
    {
        return Project::find()
            ->where(['status' => [Project::STATUS_PUBLISHED, Project::STATUS_DRAFT]])
            ->leftJoin('image i', 'i.project_id = project.id')
            ->groupBy('project.id')
            ->having('count(i.id) = 0')->all();
    }

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
