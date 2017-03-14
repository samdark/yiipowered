<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProjectFilterForm extends Model
{
    public $tags;
    public $title;
    public $url;
    public $opensource;
    public $featured;
    public $yiiVersion;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'string', 'max' => 255],
            [['opensource'], 'in', 'range' => array_keys($this->getOpenSourceOptions())],
            [['featured'], 'boolean'],
            [['yiiVersion'], 'in', 'range' => array_keys(Project::versions())],
            [['tags'], 'safe'],
        ];
    }

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'tags'       => \Yii::t('project', 'Tags'),
            'title'      => \Yii::t('project', 'Title'),
            'url'        => \Yii::t('project', 'URL'),
            'opensource' => \Yii::t('project', 'OpenSource'),
            'featured'   => \Yii::t('project', 'Only Featured'),
            'yiiVersion' => \Yii::t('project', 'Yii Version'),
        ];
    }


    /**
     * @return array
     */
    public function getOpenSourceOptions()
    {
        return [
            '1' => \Yii::t('project', 'Yes'),
            '0' => \Yii::t('project', 'No'),
        ];
    }


    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function getDataProvider()
    {
        $query = Project::find()
                        ->with('images')
                        ->publishedOrEditable()
                        ->orderBy('created_at DESC');

        if ($this->tags !== null) {
            $tags = (array)$this->tags;
            $query->allTagValues($tags);
        }

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['yii_version' => $this->yiiVersion]);
        $query->andFilterWhere(['is_opensource' => $this->opensource]);

        if ($this->featured) {
            $query->andWhere(['is_featured' => true]);
        }

        return new ActiveDataProvider([
            'query'      => $query,
            'pagination' => ['pageSize' => Yii::$app->params['project.pagesize']],
        ]);
    }


}
