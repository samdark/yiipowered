<?php

namespace app\models;

use app\components\Language;
use creocoder\taggable\TaggableBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer              $id
 * @property string               $title
 * @property string               $slug
 * @property string               $url
 * @property integer              $is_opensource
 * @property string               $source_url
 * @property integer              $created_by
 * @property integer              $updated_by
 * @property integer              $status
 * @property integer              $created_at
 * @property integer              $updated_at
 * @property integer              $is_featured
 * @property string               $yii_version
 * @property string               $tagValues
 *
 * @property Image[]              $images
 * @property User                 $updatedBy
 * @property User                 $createdBy
 * @property ProjectTag[]         $projectTags
 * @property Tag[]                $tags
 * @property User[]               $users
 * @property Vote[]               $votes
 * @property User[]               $voters
 * @property ProjectDescription[] $descriptions
 */
class Project extends \yii\db\ActiveRecord
{
    const SCENARIO_MANAGE = 'manage';

    const STATUS_DELETED   = 0;
    const STATUS_DRAFT     = 10;
    const STATUS_PUBLISHED = 20;

    const YII_VERSION_11 = '1.1';
    const YII_VERSION_20 = '2.0';

    private $_description;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class'     => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            [
                'class' => TaggableBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'yii_version'], 'required'],
            [['is_opensource', 'is_featured'], 'boolean'],
            [['title', 'url', 'source_url'], 'string', 'max' => 255],
            [['url', 'source_url'], 'url'],
            [['yii_version'], 'in', 'range' => array_keys(self::versions())],
            [['description', 'tagValues'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $defaultAttributes = ['title', 'url', 'is_opensource', 'source_url', 'yii_version', 'description', 'status', 'tagValues'];

        return [
            self::SCENARIO_DEFAULT => $defaultAttributes,
            self::SCENARIO_MANAGE  => array_merge($defaultAttributes, ['is_featured']),
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('project', 'ID'),
            'title'         => Yii::t('project', 'Title'),
            'slug'          => Yii::t('project', 'Slug'),
            'url'           => Yii::t('project', 'URL'),
            'is_opensource' => Yii::t('project', 'Is OpenSource'),
            'source_url'    => Yii::t('project', 'Source URL'),
            'created_by'    => Yii::t('project', 'Created By'),
            'updated_by'    => Yii::t('project', 'Updated By'),
            'status'        => Yii::t('project', 'Status'),
            'created_at'    => Yii::t('project', 'Created At'),
            'updated_at'    => Yii::t('project', 'Updated At'),
            'is_featured'   => Yii::t('project', 'Is Featured'),
            'yii_version'   => Yii::t('project', 'Yii Version'),
            'description'   => Yii::t('project', 'Description in {language}', ['language' => Language::current()]),
            'tagValues'     => Yii::t('project', 'Tags'),
        ];
    }

    /**
     * @return ProjectQuery
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['project_id' => 'id'])->inverseOf('project');
    }

    /**
     * @return string
     */
    public function getPlaceholderUrl()
    {
        return '/img/project_no_image.png';
    }

    /**
     * @return string
     */
    public function getPrimaryImageThumbnail()
    {
        if (empty($this->images)) {
            return $this->getPlaceholderUrl();
        }

        return $this->images[0]->getThumbnailUrl();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])->inverseOf('projects');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->inverseOf('projects0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTags()
    {
        return $this->hasMany(ProjectTag::className(), ['project_id' => 'id'])->inverseOf('project');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%project_tag}}', ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%project_user}}', ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['project_id' => 'id'])->inverseOf('project');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoters()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%vote}}', ['project_id' => 'id']);
    }

    /**
     * Returns description for a language specified or for current application language
     *
     * @param string $language
     *
     * @return string
     */
    public function getDescription($language = null)
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        $descriptions = $this->descriptions;
        if ($descriptions === []) {
            return '';
        }

        if (isset($descriptions[$language])) {
            return $descriptions[$language]->content;
        }

        return reset($descriptions)->content;
    }

    /**
     * @param $value
     */
    public function setDescription($value)
    {
        $this->_description = $value;
    }

    /**
     * @return array
     */
    public static function versions()
    {
        return [
            self::YII_VERSION_20 => self::YII_VERSION_20,
            self::YII_VERSION_11 => self::YII_VERSION_11,
        ];
    }

    /**
     * @return array
     */
    public static function statuses()
    {
        return [
            self::STATUS_DRAFT     => Yii::t('project', 'Draft'),
            self::STATUS_PUBLISHED => Yii::t('project', 'Published'),
            self::STATUS_DELETED   => Yii::t('project', 'Deleted'),
        ];
    }

    /**
     * @return mixed|string
     */
    public function getStatusLabel()
    {
        $statuses = self::statuses();
        if (isset($statuses[$this->status])) {
            return $statuses[$this->status];
        }

        return Yii::t('project', 'Unknown');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptions()
    {
        return $this
            ->hasMany(ProjectDescription::className(), ['project_id' => 'id'])
            ->orderBy(new Expression("language = 'en-US' DESC"))
            ->indexBy('language');
    }

    /**
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->_description !== null) {
            $this->saveDescription($this->_description);
        }

        if ($insert) {
            $this->addCurrentUser();
        }
    }

    /**
     * @return bool
     */
    private function addCurrentUser()
    {
        $projectUser             = new ProjectUser();
        $projectUser->project_id = $this->id;
        $projectUser->user_id    = Yii::$app->getUser()->getId();

        return $projectUser->save();
    }

    /**
     * @param $content
     *
     * @return bool
     */
    private function saveDescription($content)
    {
        $language    = Yii::$app->language;
        $description = ProjectDescription::find()->where([
            'project_id' => $this->id,
            'language'   => $language,
        ])->one();

        if ($description && empty($content)) {
            return $description->delete() !== false;
        }

        if (!$description) {
            $description             = new ProjectDescription();
            $description->project_id = $this->id;
            $description->language   = $language;
        }

        $description->content = $content;

        return $description->save();
    }

    /**
     * @return string
     */
    public function getStatusClass()
    {
        switch ($this->status) {
            case Project::STATUS_DELETED:
                return 'status-deleted';
            case Project::STATUS_DRAFT:
                return 'status-draft';
            case Project::STATUS_PUBLISHED:
                return 'status-published';
        }

        return 'status-unknown';
    }

    /**
     * @param $limit
     *
     * @return \yii\db\Query
     */
    public function getFeaturedProjectsQuery($limit)
    {
        return static::find()
                     ->with('images')
                     ->featured()
                     ->publishedOrEditable()
                     ->orderBy('created_at DESC')
                     ->limit($limit);
    }

    /**
     * @param $limit
     *
     * @return \yii\db\Query
     */
    public function getNewProjectsQuery($limit)
    {
        return static::find()
                     ->with('images')
                     ->featured(false)
                     ->publishedOrEditable()
                     ->orderBy('created_at DESC')
                     ->limit($limit);
    }

    /**
     * @param int $limit
     *
     * @return \app\models\Project[]
     */
    public function getRecent($limit)
    {
        static::find()
              ->with('images')
              ->where(['status' => static::STATUS_PUBLISHED])
              ->orderBy('created_at DESC')
              ->limit($limit)
              ->all();
    }

    /**
     * @param bool $schema
     *
     * @return string
     */
    public function getPageUrl($schema = true)
    {
        return Url::to(['/project/view', 'id' => $this->id, 'slug' => $this->slug], $schema);
    }
}
