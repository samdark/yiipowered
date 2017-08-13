<?php

namespace app\modules\api1\controllers;

use app\components\UserPermissions;
use app\models\User;
use app\modules\api1\components\Controller;
use app\modules\api1\models\Project;
use app\modules\api1\models\ProjectSearch;
use app\modules\api1\models\Vote;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
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
     * @param int $id
     *
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate($id)
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage project.');
        }

        $project = $this->findProject($id, $user);
        if (!UserPermissions::canManageProject($project)) {
            throw new ForbiddenHttpException(Yii::t('project', 'You can not update this project.'));
        }
        $project->scenario = Project::SCENARIO_MANAGE;
        
        if ($project->load(Yii::$app->request->getBodyParams(), '')) {
            if (!$project->save()) {
                throw new ServerErrorHttpException('Unable to save project: ' . json_encode($project->getErrors()));   
            }
        }
        
        Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * @param int $id
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionVote($id)
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage voting.');
        }
        
        $project = Project::findOne($id);
        
        if (!$project) {
            throw new NotFoundHttpException("The requested project does not exist.");
        }
        
        $value = Yii::$app->request->getBodyParam('value');
        
        $vote = Vote::getVote($project->id, $user->id);
        if (!$vote || $vote->value != $value) {
            if (!$vote) {
                $vote = new Vote();
                $vote->project_id = $project->id;
            }
            $vote->value = $value;

            if (!$vote->save()) {
                throw new ServerErrorHttpException('Unable to save vote: ' . json_encode($vote->getErrors()));
            }
        }
        
        return [
            'votingResult' => $project->votingResult
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'update' => ['PUT', 'PATCH'],
            'vote' => ['PUT', 'PATCH']
        ];
    }

    /**
     * @param int $projectId
     * @param User $user
     *
     * @return Project
     * @throws NotFoundHttpException
     */
    protected function findProject($projectId, User $user)
    {
        /** @var Project $project */
        $project = Project::find()
            ->where(['id' => $projectId])
            ->hasUser($user)
            ->one();

        if ($project) {
            return $project;
        }
        
        throw new NotFoundHttpException("The requested project does not exist.");
    }
}