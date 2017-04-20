<?php

namespace app\modules\api1\controllers;

use app\modules\api1\components\Controller;
use app\modules\api1\models\Project;
use app\modules\api1\models\ProjectSearch;
use yii\web\BadRequestHttpException;

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
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
        ];
    }
}