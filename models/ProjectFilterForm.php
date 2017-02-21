<?php


namespace app\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProjectFilterForm extends Model
{
    public $title;
    public $url;
    public $opensource;
    public $featured;
    public $yiiVersion;

    public function rules()
    {
        return [
            [['title', 'url'], 'string', 'max' => 255],
            [['opensource'], 'in', 'range' => array_keys($this->getOpenSourceOptions())],
            [['featured'], 'boolean'],
            [['yiiVersion'], 'in', 'range' => array_keys(Project::versions())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => \Yii::t('project', 'Title'),
            'url' => \Yii::t('project', 'URL'),
            'opensource' => \Yii::t('project', 'OpenSource'),
            'featured' => \Yii::t('project', 'Only Featured'),
            'yiiVersion' => \Yii::t('project', 'Yii Version'),
        ];
    }


    public function getOpenSourceOptions()
    {
        return [
            '1' => \Yii::t('project', 'Yes'),
            '0' => \Yii::t('project', 'No'),
        ];
    }


    public function getDataProvider()
    {
        $query = Project::find()
            ->publishedOrEditable()
            ->orderBy('created_at DESC');

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['yii_version' => $this->yiiVersion]);
        $query->andFilterWhere(['is_opensource' => $this->opensource]);
        $query->andFilterWhere(['is_featured' => $this->featured]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);
    }


}
