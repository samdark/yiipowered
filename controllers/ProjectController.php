<?php

namespace app\controllers;

use app\components\feed\Feed;
use app\components\feed\Item;
use app\models\Project;
use app\notifier\NewProjectNotification;
use app\notifier\Notifier;
use Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProjectController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['admin', 'create', 'update', 'delete'], //only be applied to
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['admin'],
                        'roles' => ['manageProjects'],
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Project::find()->where(['status' => Project::STATUS_PUBLISHED])->orderBy('created_at DESC'),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $notifier = new Notifier(new NewProjectNotification($model));
            $notifier->sendEmails();
            Yii::$app->session->setFlash('project.project_successfully_added');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionRss()
    {
        /** @var Project[] $projects */
        $projects = Project::find()->where(['status' => Project::STATUS_PUBLISHED])->orderBy('created_at DESC')->limit(50)->all();

        $feed = new Feed();
        $feed->title = 'YiiPowered';
        $feed->link = Url::to('');
        $feed->selfLink = Url::to(['project/rss'], true);
        $feed->description = 'Yii powered projects';
        $feed->language = 'en';
        $feed->setWebMaster('sam@rmcreative.ru', 'Alexander Makarov');
        $feed->setManagingEditor('sam@rmcreative.ru', 'Alexander Makarov');

        foreach ($projects as $project) {
            $item = new Item();
            $item->title = $project->title;
            $item->link = Url::to(['project/view', 'id' => $project->id], true);
            $item->guid = Url::to(['project/view', 'id' => $project->id], true);
            $item->description = HtmlPurifier::process(Markdown::process($project->getDescription()));

            if (!empty($project->link)) {
                $item->description .= Html::a(Html::encode($project->url), $project->url);
            }

            $item->pubDate = $project->created_at;
            $item->setAuthor('noreply@yiipowered.com', 'YiiPowered');
            $feed->addItem($item);
        }

        $feed->render();
    }

    public function actionAdmin($status)
    {
        $query = Project::find()->orderBy('created_at DESC');
        $query->andWhere(['status' => $status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('admin', [
            'status' => $status,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'slug' => $model->slug]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['admin']);
    }


    public function actionView($id, $slug)
    {
        $project = $this->findModel([
            'id' => $id,
            'slug' => $slug,
            'status' => Project::STATUS_PUBLISHED,
        ]);

        return $this->render('view', [
            'model' => $project,
        ]);
    }

    protected function findModel($condition)
    {
        if (($model = Project::findOne($condition)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
