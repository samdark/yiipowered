<?php

namespace app\widgets;

use Yii;
use app\models\Project;
use app\models\Vote as VoteModel;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;

class Vote extends Widget
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
            throw new InvalidConfigException('Vote widget project property is not set.');
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
        
        $buttonClass = [];
        $buttonClass[VoteModel::VALUE_UP] = ['button', 'up'];
        $buttonClass[VoteModel::VALUE_DOWN] = ['button', 'down'];

        $vote = VoteModel::getVote($this->project->id, Yii::$app->user->id);
        if ($vote) {
            $buttonClass[$vote->value][] = 'disabled';
        }
        
        $voteUpHtml = Html::tag(
            'span',
            Html::icon('thumbs-up'),
            [
                'data-id' => $this->project->id,
                'data-endpoint' => Url::to(['/api1/project/voting', 'id' => $this->project->id]),
                'data-value' => VoteModel::VALUE_UP,
                'class' => $buttonClass[VoteModel::VALUE_UP],
                'title' => Yii::t('vote', 'Up'),
            ]
        );

        $voteDownHtml = Html::tag(
            'span',
            Html::icon('thumbs-down'),
            [
                'data-endpoint' => Url::to(['/api1/project/voting', 'id' => $this->project->id]),
                'data-value' => VoteModel::VALUE_DOWN,
                'class' => $buttonClass[VoteModel::VALUE_DOWN],
                'title' => Yii::t('vote', 'Down'),
            ]
        );

        $voteValueHtml = Html::tag(
            'span',
            $this->project->voteValue,
            [
                'class' => 'value',
            ]
        );

        return Html::tag('div', $voteDownHtml . $voteValueHtml . $voteUpHtml, ['class' => 'vote']);
    }
}
