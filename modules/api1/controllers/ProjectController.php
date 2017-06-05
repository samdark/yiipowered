<?php

namespace app\modules\api1\controllers;

use app\modules\api1\components\Controller;
use app\modules\api1\models\Image;
use app\modules\api1\models\Project;
use app\modules\api1\models\ProjectSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $projectSearch = new ProjectSearch();
        $projectSearch->load(\Yii::$app->request->get());
        if (!$projectSearch->validate()) {
            throw new BadRequestHttpException('Invalid parameters: ' . json_encode($projectSearch->getErrors()));
        }

        return $projectSearch->getDataProvider();
    }

    public function actionView($id)
    {
        return Project::find()->where(['id' => $id])->published()->one();
    }

    /**
     * Update primary image for project.
     * 
     * @param int $id Id of the project for which to update the primary image.
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionUpdatePrimaryImage($id)
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage image.');
        }

        $project = $this->findProject($id, $user->id);
        
        $request = Yii::$app->getRequest();
        $imageId = $request->getBodyParam('imageId');
        
        /** @var Image $image */
        $image = Image::find()
            ->andWhere([
                'id' => $imageId,
                'project_id' => $project->id
            ])
            ->limit(1)
            ->one();

        if (!$image) {
            throw new NotFoundHttpException("Image {$imageId} does not exist.");
        }

        $project->primary_image_id = $image->id;
        if (!$project->save()) {
            throw new ServerErrorHttpException('Unable to save primary image: ' . json_encode($project->getErrors()));
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * Return primary image for project.
     *
     * @param int $id Id of the project for which to return the primary image.
     *
     * @return Image
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionViewPrimaryImage($id)
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage image.');
        }

        $project = $this->findProject($id, $user->id);
        
        $primaryImage = $project->primaryImage;
        if ($primaryImage) {
            return $primaryImage;
        }

        throw new NotFoundHttpException("Primary image not selected.");
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'view-primary-image' => ['GET', 'HEAD'],
            'update-primary-image' => ['PATCH', 'PUT'],
        ];
    }

    /**
     * @param int $projectId
     * @param int $userId
     *
     * @return Project
     * @throws NotFoundHttpException
     */
    protected function findProject($projectId, $userId)
    {
        /** @var Project $project */
        $project = Project::find()
            ->alias('p')
            ->innerJoinWith([
                'users' => function (ActiveQuery $query) {
                    $query->alias('u');
                }
            ], false)
            ->where([
                'p.id' => $projectId,
                'u.id' => $userId,
            ])
            ->one();

        if ($project) {
            return $project;
        }
        
        throw new NotFoundHttpException("The requested project does not exist.");
    }
}