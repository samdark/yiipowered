<?php

namespace app\controllers;

use app\models\Project;
use app\models\Rating;
use app\models\Bookmark;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
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
                    'bookmark' => ['post']
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
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionBookmark($projectId)
    {
        /** @var Project $project */
        $project = Project::findOne($projectId);

        if (!$project) {
            throw new NotFoundHttpException();
        }
        
        $state = Yii::$app->request->post('state');
        if ($state === null) {
            throw new BadRequestHttpException('Missing required parameters: status.');
        }

        Bookmark::changeState($projectId, Yii::$app->user->id, $state);

        return [
            'bookmarkExists' => (int) Bookmark::exists($projectId, Yii::$app->user->id)
        ];
    }
}
