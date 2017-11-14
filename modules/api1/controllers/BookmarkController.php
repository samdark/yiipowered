<?php

namespace app\modules\api1\controllers;

use app\modules\api1\components\Controller;
use app\modules\api1\models\Bookmark;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class BookmarkController extends Controller
{
    /**
     * @return ActiveDataProvider
     * @throws UnauthorizedHttpException
     */
    public function actionIndex()
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage bookmarks.');
        }

        return new ActiveDataProvider([
            'query' => Bookmark::find()->where(['user_id' => $user->id]),
        ]);
    }

    /**
     * @return Bookmark|array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionCreate()
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage bookmarks.');
        }

        $request = Yii::$app->getRequest();
        $id = $request->getBodyParam('id');
        $bookmark = $this->findBookmark($id, $user->id, false);

        if (!$bookmark) {
            $bookmark = new Bookmark();
            $bookmark->project_id = $id;
            if (!$bookmark->save()) {
                throw new ServerErrorHttpException('Unable to save bookmark: ' . json_encode($bookmark->getErrors()));
            }
            Yii::$app->getResponse()->setStatusCode(201);
        }

        return $bookmark;
    }

    /**
     * @param int $id
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new UnauthorizedHttpException('User should be authorized in order to manage bookmarks.');
        }

        $bookmark = $this->findBookmark($id, $user->id);
        if ($bookmark->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete bookmark.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * @param int $projectID
     * @param int $userID
     * @param bool $required
     * @return Bookmark|array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findBookmark($projectID, $userID, $required = true)
    {
        $bookmark = Bookmark::find()->where([
            'project_id' => $projectID,
            'user_id' => $userID,
        ])->one();

        if (!$bookmark && $required) {
            throw new NotFoundHttpException("Project $projectID is not bookmarked.");
        }

        return $bookmark;
    }
}