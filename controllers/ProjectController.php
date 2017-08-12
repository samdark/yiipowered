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
use app\models\User;
use app\models\Vote;
use app\notifier\NewProjectNotification;
use app\notifier\Notifier;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class ProjectController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete-image', 'bookmarks'], //only be applied to
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete-image', 'bookmarks', 'publish', 'draft'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'publish' => ['post'],
                    'draft' => ['post']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $limit = Yii::$app->params['project.pagesize'];

        $featuredProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Project::find()
                ->with('images')
                ->with('tags')
                ->featured()
                ->publishedOrEditable()
                ->freshFirst()
                ->limit($limit)
        ]);

        $newProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Project::find()
                ->with('images')
                ->with('tags')
                ->featured(false)
                ->publishedOrEditable()
                ->freshFirst()
                ->limit($limit)
        ]);

        $projectsCount = (clone $newProvider->query)->limit(null)->count();
        $seeMoreCount = $projectsCount - $limit;

        return $this->render('index', [
            'featuredProvider' => $featuredProvider,
            'newProvider' => $newProvider,
            'projectsCount' => $projectsCount,
            'seeMoreCount' => $seeMoreCount
        ]);
    }

    public function actionList()
    {
        $filterForm = new ProjectFilterForm();
        $filterForm->load(Yii::$app->request->get());

        $tagsDataProvider = new ActiveDataProvider([
            'query' => Tag::find()->top(10),
            'pagination' => false,
        ]);

        return $this->render('list', [
            'dataProvider' => $filterForm->getDataProvider(),
            'tagsDataProvider' => $tagsDataProvider,
            'filterForm' => $filterForm,
        ]);
    }

    /**
     * Return Top projects.
     *
     * @return string
     */
    public function actionTopProjects()
    {
        $countTopProjects = Yii::$app->params['project.countTopProjects'];
        
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Project::find()
                ->with('images')
                ->with('tags')
                ->published()
                ->innerJoin([
                    'v' => Vote::find()
                        ->select([
                            'project_id', 
                            'sumValue' => new Expression('SUM(value)'), 
                            'countVote' => new Expression('COUNT(*)')
                        ])
                        ->groupBy('project_id')
                        ->having('sumValue >= 0')
                ], 'v.project_id = project.id')
                ->orderBy([
                    'v.sumValue' => SORT_DESC,
                    'v.countVote' => SORT_DESC,
                ])
                ->limit($countTopProjects)
        ]); 

        return $this->render('topProjects', [
            'dataProvider' => $dataProvider,
            'countTopProjects' => $countTopProjects
        ]);
    }
    
    /**
     * Return bookmark projects.
     * 
     * @return string
     */
    public function actionBookmarks()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $user->getBookmarkedProjects(),
        ]);
        
        return $this->render('bookmarks', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new Project();
        if (UserPermissions::canManageProjects()) {
            $model->setScenario(Project::SCENARIO_MANAGE);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $notifier = new Notifier(new NewProjectNotification($model));
            $notifier->sendEmails();
            return $this->redirect(['screenshots', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionRss()
    {
        /** @var Project[] $projects */
        $projects = Project::find()
            ->with('images', 'users')
            ->published()
            ->freshFirst()
            ->limit(50)
            ->all();

        $feed = new Feed();
        $feed->title = 'YiiPowered';
        $feed->link = Url::to('');
        $feed->selfLink = Url::to(['project/rss'], true);
        $feed->description = 'Yii powered projects';
        $feed->language = 'en';
        $feed->setWebMaster('sam@rmcreative.ru', 'Alexander Makarov');
        $feed->setManagingEditor('sam@rmcreative.ru', 'Alexander Makarov');

        foreach ($projects as $project) {
            $url = Url::to(['project/view', 'id' => $project->id, 'slug' => $project->slug], true);
            $item = new Item();
            $item->title = $project->title;
            $item->link = $url;
            $item->guid = $url;

            $imageTag = '';

            if (!empty($project->images)) {
                $imageTag = Html::img($project->images[0]->getThumbnailAbsoluteUrl()) . '<br>';
            }

            $item->description = $imageTag . HtmlPurifier::process(Markdown::process($project->getDescription()));

            if (!empty($project->link)) {
                $item->description .= Html::a(Html::encode($project->url), $project->url);
            }

            $item->pubDate = $project->created_at;
            $authors = [];
            foreach ($project->users as $user) {
                $authors[] = '@' . $user->username;
            }

            $item->setAuthor('noreply@yiipowered.com', implode(', ', $authors));
            $feed->addItem($item);
        }

        $feed->render();
    }

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
            return $this->redirect(['screenshots', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionScreenshots($id)
    {
        $model = $this->findModel(['id' => $id]);

        if (!UserPermissions::canManageProject($model)) {
            throw new ForbiddenHttpException(Yii::t('project', 'You can not update this project.'));
        }

        if (Yii::$app->user->can(UserPermissions::MANAGE_PROJECTS)) {
            $model->setScenario(Project::SCENARIO_MANAGE);
        }

        $imageUploadForm = null;

        if (UserPermissions::canManageProject($model)) {
            $imageUploadForm = new ImageUploadForm($id);
            if ($imageUploadForm->load(Yii::$app->request->post())) {
                $imageUploadForm->file = UploadedFile::getInstance($imageUploadForm, 'file');
                if ($imageUploadForm->upload()) {
                    return $this->refresh();
                }
            }
        }

        return $this->render('screenshots', [
            'model' => $model,
            'imageUploadForm' => $imageUploadForm
        ]);
    }

    public function actionPreview($id)
    {
        $project = $this->findModel([
            'id' => $id,
        ]);

        return $this->render('preview', [
            'model' => $project,
        ]);
    }

    public function actionPublish($id)
    {
        $project = $this->findModel(['id' => $id]);

        if (!UserPermissions::canManageProject($project)) {
            throw new ForbiddenHttpException(Yii::t('project', 'You can not update this project.'));
        }

        $project->publish();
        Yii::$app->session->setFlash('project.project_successfully_added');

        return $this->redirect(['view', 'id' => $project->id, 'slug' => $project->slug]);
    }

    public function actionDraft($id)
    {
        $project = $this->findModel(['id' => $id]);

        if (!UserPermissions::canManageProject($project)) {
            throw new ForbiddenHttpException(Yii::t('project', 'You can not update this project.'));
        }

        $project->draft();

        return $this->redirect(['/user/view', 'id' => Yii::$app->user->id]);
    }

    public function actionView($id, $slug)
    {
        $project = $this->findModel([
            'id' => $id,
            'slug' => $slug,
        ]);

        return $this->render('view', [
            'model' => $project,
        ]);
    }

    /**
     * @param array $condition
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
     * @return Response
     */
    public function actionAutocompleteTags($term)
    {
        $tags = Tag::find()->where(['like', 'name', $term])->limit(10)->all();
        return $this->asJson(ArrayHelper::getColumn($tags, 'name'));
    }

    /**
     * Returns an image
     *
     * @param int $imageId id of the image to return
     *
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionImageOriginal($imageId)
    {
        /** @var Image $image */
        $image = Image::find()
            ->with('project')
            ->where(['id' => $imageId])
            ->limit(1)
            ->one();

        if (!$image) {
            throw new NotFoundHttpException('Image not found.');
        }

        if (!UserPermissions::canManageProject($image->project)) {
            throw new ForbiddenHttpException("You don't have access to this image.");
        }

        return Yii::$app->getResponse()->sendFile($image->getOriginalPath(), $image->getOriginalFilename());
    }
}
