<?php

namespace app\widgets\star;

use app\models\Project;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Url;

class Star extends Widget
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
     * @return bool|string
     */
    public function run()
    {
        $starValue = 0;
        if (!Yii::$app->user->isGuest) {
            $starValue = \app\models\Star::getStarValue($this->project->id, Yii::$app->user->id);
        }
    
        return $this->render('star', [
            'ajaxUrl' => Url::to(['ajax/star', 'projectId' => $this->project->id]),
            'starValue' => $starValue,
            'starCount' => $this->project->starCount,
        ]);
    }
}
