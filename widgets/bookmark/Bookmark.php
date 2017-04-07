<?php

namespace app\widgets\bookmark;

use Yii;
use app\models\Project;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Url;

class Bookmark extends Widget
{
    /**
     * @var Project
     */
    public $project;
    
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->project === null) {
            throw new InvalidConfigException('Star widget property project is not set.');
        }
    }
    
    /**
     * @return string
     */
    public function run()
    {
        $bookmarkExists = false;
        if (!Yii::$app->user->isGuest) {
            $bookmarkExists = \app\models\Bookmark::exists($this->project->id, Yii::$app->user->id);
        }
        return $this->render('bookmark', [
            'ajaxUrl' => Url::to(['ajax/bookmark', 'projectId' => $this->project->id]),
            'bookmarkExists' => $bookmarkExists,
        ]);
    }
}
