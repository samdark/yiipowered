<?php

namespace app\controllers;

use app\models\Project;
use app\models\Rating;
use app\models\Star;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * AjaxController handles several ajax actions in the background
 */
class AjaxController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'star' => ['post']
                ],
            ],
        ];
    }
    
    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return parent::beforeAction($action);
    }
    
    /**
     * @param $projectId
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionStar($projectId)
    {
        /** @var Project $project */
        $project = Project::findOne($projectId);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $star = Star::changeStar($projectId, Yii::$app->user->id);
        $starCount = $project->starCount;

        return [
            'star' => $star,
            'starCount' => $starCount
        ];
    }
}
