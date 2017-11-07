<?php

namespace app\widgets\comment;

use app\components\object\ObjectIdentityInterface;
use app\models\Comment as CommentForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Comment widget
 */
class Comment extends Widget
{
    /**
     * @var ActiveRecord|ObjectIdentityInterface
     */
    public $model;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->model === null) {
            throw new InvalidConfigException('Comment widget property model is not set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $commentForm = null;
        if (!Yii::$app->user->isGuest) {
            $commentForm = new CommentForm([
                'scenario' => CommentForm::SCENARIO_CREATE,
                'object_type' => $this->model->getObjectType(),
                'object_id' => $this->model->getObjectId()
            ]);
            $commentForm->loadDefaultValues();

            if ($commentForm->load(Yii::$app->request->post()) && $commentForm->save()) {
                Yii::$app->getResponse()->refresh(sprintf("#c%d", $commentForm->id))->send();
                return;
            }   
        }

        $comments = CommentForm::find()
            ->forObject($this->model)
            ->active()
            ->with('createdBy')
            ->all();

        return $this->render('comment', [
            'comments' => $comments,
            'commentForm' => $commentForm
        ]);
    }
}
