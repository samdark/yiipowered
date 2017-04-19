<?php

namespace app\widgets\bookmark;

use Yii;
use app\models\Project;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Html;
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
            throw new InvalidConfigException('Bookmark widget project property is not set.');
        }
    }
    
    /**
     * @return string
     */
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        $bookmarked = \app\models\Bookmark::exists($this->project->id, Yii::$app->user->id);


        $createClass = ['create'];
        $deleteClass = ['delete'];

        if ($bookmarked) {
            $createClass[] = 'hide';
        } else {
            $deleteClass[] = 'hide';
        }

        $create = Html::tag(
            'span',
            Html::icon('bookmark'),
            [
                'data-id' => $this->project->id,
                'data-endpoint' => Url::to(['/api1/bookmark/create']),
                'class' => $createClass,
                'title' => Yii::t('bookmark', 'Add to bookmarks'),
            ]
        );

        $delete = Html::tag(
            'span',
            Html::icon('bookmark'),
            [
                'data-endpoint' => Url::to(['/api1/bookmark/delete', 'id' => $this->project->id]),
                'class' => $deleteClass,
                'title' => Yii::t('bookmark', 'Remove from bookmarks'),
            ]
        );

        return Html::tag('div', $create . $delete, ['class' => 'bookmark']);
    }
}
