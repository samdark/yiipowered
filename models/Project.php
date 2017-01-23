<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $url
 * @property integer $is_opensource
 * @property string $source_url
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_featured
 * @property string $yii_version
 *
 * @property Image[] $images
 * @property User $updatedBy
 * @property User $createdBy
 * @property ProjectTag[] $projectTags
 * @property Tag[] $tags
 * @property ProjectUser[] $projectUsers
 * @property User[] $users
 * @property Vote[] $votes
 * @property User[] $voters
 * @property ProjectDescription[] $descriptions
 */
class Project extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_PUBLISHED = 10;

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
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
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
            [['is_opensource'], 'boolean'],
            [['title', 'url', 'source_url'], 'string', 'max' => 255],
            [['yii_version'], 'in', 'range' => array_keys(self::versions())],
            [['description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),
            'url' => Yii::t('app', 'URL'),
            'is_opensource' => Yii::t('app', 'Is OpenSource'),
            'source_url' => Yii::t('app', 'Source URL'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_featured' => Yii::t('app', 'Is Featured'),
            'yii_version' => Yii::t('app', 'Yii Version'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['project_id' => 'id'])->inverseOf('project');
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
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['project_id' => 'id'])->inverseOf('project');
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

    public function setDescription($value)
    {
        $this->_description = $value;
    }

    public static function versions()
    {
        return [
            self::YII_VERSION_11 => self::YII_VERSION_11,
            self::YII_VERSION_20 => self::YII_VERSION_20,
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DELETED => Yii::t('project', 'Deleted'),
            self::STATUS_PUBLISHED => Yii::t('project', 'Published'),
        ];
    }

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
            ->orderBy(new Expression("language = 'en-US'"))
            ->indexBy('language');
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->_description !== null) {
            $this->saveDescription($this->_description);
        }

        return true;
    }

    private function saveDescription($content)
    {
        $language = Yii::$app->language;
        $description = ProjectDescription::find()->where([
            'project_id' => $this->id,
            'language' => $language,
        ])->one();

        if ($description && empty($content)) {
            return $description->delete() !== false;
        }

        if (!$description) {
            $description = new ProjectDescription();
            $description->project_id = $this->id;
            $description->language = $language;
        }

        $description->content = $content;
        return $description->save();
    }
}
