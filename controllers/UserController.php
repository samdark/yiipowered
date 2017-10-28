<?php

namespace app\controllers;

use app\components\UserPermissions;
use Yii;
use app\models\User;
use app\models\Project;
use yii\authclient\Collection;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'update'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'delete'],
                        'roles' => ['manage_users'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays user profile
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        /** @var User $user */
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('No such user.');
        }

        $authClients = [];
        if (Yii::$app->user->id == $user->id) {
            // get clients user isn't connected with yet
            $auths = $user->auths;
            /** @var Collection $clientCollection */
            $clientCollection = Yii::$app->authClientCollection;
            $authClients = $clientCollection->getClients();
            foreach ($auths as $auth) {
                unset($authClients[$auth->source]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Project::find()->hasUser($user)->available()->freshFirst(),
            'pagination' => ['pageSize' => Yii::$app->params['project.pagesize']],
        ]);

        return $this->render('view', [
            'model' => $user,
            'dataProvider' => $dataProvider,
            'authClients' => $authClients,
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!UserPermissions::canManageUser($model)) {
            throw new ForbiddenHttpException(Yii::t('user', 'You can not update this user.'));
        }

        if (Yii::$app->user->can(UserPermissions::MANAGE_USERS)) {
            $model->setScenario(User::SCENARIO_MANAGE);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');
            if($model->uploadAvatar() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('user', 'Your profile has been successfully updated.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('user', 'Can not save your profile.'));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_DELETED;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
