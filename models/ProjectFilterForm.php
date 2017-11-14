<?php


namespace app\models;


use app\components\UserPermissions;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 *
 * @property-read array $openSourceOptions
 * @property \yii\data\ActiveDataProvider $dataProvider
 */
class ProjectFilterForm extends Model
{
    public $tags = [];
    public $title;
    public $url;
    public $opensource;
    public $featured;
    public $yiiVersion;

    public $status;

    public function rules()
    {
        return [
            [['title', 'url'], 'string', 'max' => 255],
            [['opensource'], 'in', 'range' => array_keys($this->getOpenSourceOptions())],
            [['featured'], 'boolean'],
            [['yiiVersion'], 'in', 'range' => array_keys(Project::versions())],
            ['status', 'in', 'range' => Project::$availableStatusIds],
            [['tags'], 'safe']
        ];
    }

    public function formName()
    {
        return '';
    }

    public function hasTag($tag)
    {
        return in_array($tag, (array)$this->tags, true);
    }

    public function attributeLabels()
    {
        return [
            'tags' => \Yii::t('project', 'Tags'),
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
            '1' => \Yii::t('project', 'Open source'),
            '0' => \Yii::t('project', 'Not open source'),
        ];
    }


    public function getDataProvider()
    {
        $query = Project::find()
            ->with('images')
            ->publishedOrEditable()
            ->orderBy('created_at DESC');

        if (!empty($this->tags)) {
            $tags = (array) $this->tags;
            $query->allTagValues($tags);
        }

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['yii_version' => $this->yiiVersion]);
        $query->andFilterWhere(['is_opensource' => $this->opensource]);

        if ($this->status !== null && UserPermissions::canManageProjects()) {
            $query->andFilterWhere(['status' => $this->status]);
        }

        if ($this->featured) {
            $query->andWhere(['is_featured' => true]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->params['project.pagesize']],
        ]);
    }


}
