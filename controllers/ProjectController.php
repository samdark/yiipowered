<?php

namespace app\controllers;

use app\components\feed\Feed;
use app\components\feed\Item;
use app\components\UserPermissions;
use app\models\Image;
use app\models\ImageUploadForm;
use app\models\Project;
use app\models\ProjectFilterForm;
use app\models\Tag;
use app\notifier\NewProjectNotification;
use app\notifier\Notifier;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * Class ProjectController
 *
 * @package app\controllers
 */
class ProjectController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['create', 'update', 'delete-image'], //only be applied to
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['create', 'update', 'delete-image'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $limit = Yii::$app->params['project.pagesize'];

        $project          = new Project();
        $featuredProvider = new ActiveDataProvider([
            'pagination' => false,
            'query'      => $project->getFeaturedProjectsQuery($limit),
        ]);

        $newProvider = new ActiveDataProvider([
            'pagination' => false,
            'query'      => $project->getNewProjectsQuery($limit),
        ]);

        return $this->render('index', [
            'featuredProvider' => $featuredProvider,
            'newProvider'      => $newProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionList()
    {
        $filterForm = new ProjectFilterForm();
        $filterForm->load(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $filterForm->getDataProvider(),
            'filterForm'   => $filterForm,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Project();
        if (UserPermissions::canManageProjects()) {
            $model->setScenario(Project::SCENARIO_MANAGE);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $notifier = new Notifier(new NewProjectNotification($model));
            $notifier->sendEmails();
            Yii::$app->session->setFlash('project.project_successfully_added');

            return $this->redirect(['view', 'id' => $model->id, 'slug' => $model->slug]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     *Rss feed action
     */
    public function actionRss()
    {
        $project  = new Project();
        $projects = $project->getRecent(50);

        $feed              = new Feed();
        $feed->title       = 'YiiPowered';
        $feed->link        = Url::to('');
        $feed->selfLink    = Url::to(['project/rss'], true);
        $feed->description = 'Yii powered projects';
        $feed->language    = 'en';
        $feed->setWebMaster('sam@rmcreative.ru', 'Alexander Makarov');
        $feed->setManagingEditor('sam@rmcreative.ru', 'Alexander Makarov');

        foreach ($projects as $project) {

            $projectPageUrl = $project->getPageUrl();

            $item              = new Item();
            $item->title       = $project->title;
            $item->link        = $projectPageUrl;
            $item->guid        = $projectPageUrl;
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

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(['id' => $id]);

        if (!UserPermissions::canManageProject($model)) {
            throw new ForbiddenHttpException(Yii::t('project', 'You can not update this project.'));
        }

        if (Yii::$app->user->can(UserPermissions::MANAGE_PROJECTS)) {
            $model->setScenario(Project::SCENARIO_MANAGE);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'slug' => $model->slug]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @param $slug
     *
     * @return string|\yii\web\Response
     */
    public function actionView($id, $slug)
    {
        $project = $this->findModel([
            'id'   => $id,
            'slug' => $slug,
        ]);

        $imageUploadForm = null;

        if (UserPermissions::canManageProject($project)) {
            $imageUploadForm = new ImageUploadForm($id);
            if ($imageUploadForm->load(Yii::$app->request->post())) {
                $imageUploadForm->file = UploadedFile::getInstance($imageUploadForm, 'file');
                if ($imageUploadForm->upload()) {
                    return $this->refresh();
                }
            }
        }

        return $this->render('view', [
            'model'           => $project,
            'imageUploadForm' => $imageUploadForm,
        ]);
    }

    /**
     * @param array $condition
     *
     * @return Project
     * @throws NotFoundHttpException
     */
    protected function findModel($condition)
    {
        /** @var Project $model */
        $model = Project::find()
                        ->publishedOrEditable()
                        ->andWhere($condition)
                        ->one();

        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return string
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionDeleteImage()
    {
        $id = Yii::$app->request->post('id');
        if ($id === null) {
            throw new BadRequestHttpException('Image id was not provided.');
        }

        /** @var Image $image */
        $image = Image::find()->with('project')->where(['id' => $id])->one();
        if (!$image) {
            throw new NotFoundHttpException('No image was found.');
        }

        if (!UserPermissions::canManageProject($image->project)) {
            throw new ForbiddenHttpException('You are not allowed to delete this image.');
        }

        if ($image->delete()) {
            return 'OK';
        }
        throw new ServerErrorHttpException('Unable to delete image.');
        
    }

    /**
     * @param $term
     *
     * @return Response
     */
    public function actionAutocompleteTags($term)
    {
        $tags = Tag::find()->where(['like', 'name', $term])->limit(10)->all();

        return $this->asJson(ArrayHelper::getColumn($tags, 'name'));
    }
}
