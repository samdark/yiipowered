<?php


namespace app\modules\api1\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProjectSearch extends Model
{
    public $tags;
    public $title;
    public $url;
    public $isOpenSource;
    public $isFeatured;
    public $yiiVersion;

    public function rules()
    {
        return [
            [['title', 'url'], 'string', 'max' => 255],
            [['isOpenSource'], 'in', 'range' => [0,1]],
            [['isFeatured'], 'boolean'],
            [['yiiVersion'], 'in', 'range' => array_keys(Project::versions())],
            [['tags'], 'safe'],
        ];
    }

    public function formName()
    {
        return '';
    }

    public function getDataProvider()
    {
        $query = Project::find()
            ->published()
            ->orderBy('created_at DESC');

        if ($this->tags !== null) {
            $tags = (array) $this->tags;
            $query->allTagValues($tags);
        }

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['yii_version' => $this->yiiVersion]);
        $query->andFilterWhere(['is_opensource' => $this->isOpenSource]);

        if ($this->isFeatured) {
            $query->andWhere(['is_featured' => true]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}