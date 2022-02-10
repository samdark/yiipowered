<?php

namespace app\models;

use app\components\Language;
use app\components\object\ClassType;
use app\components\object\Linkable;
use app\components\object\ObjectIdentityInterface;
use app\components\queue\ProjectDeleteJob;
use app\components\queue\ProjectShareJob;
use creocoder\taggable\TaggableBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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
 * @property string $tagValues
 * @property int $primary_image_id
 * @property boolean $published_to_twitter
 * @property boolean $verified
 * @property string $check_result
 * @property string $check_log
 *
 * @property Image[] $images
 * @property User $updatedBy
 * @property User $createdBy
 * @property ProjectTag[] $projectTags
 * @property Tag[] $tags
 * @property User[] $users
 * @property Vote[] $votes
 * @property User[] $voters
 * @property string $placeholderAbsoluteUrl
 * @property string $placeholderRelativeUrl
 * @property string $primaryImageThumbnailRelativeUrl
 * @property string $statusClass
 * @property string $primaryImageThumbnailAbsoluteUrl
 * @property string $statusLabel
 * @property string $description
 * @property ProjectDescription[] $descriptions
 * @property Image $primaryImage
 * @property int $votingResult
 */
class Project extends ActiveRecord implements Linkable, ObjectIdentityInterface
{
    const SCENARIO_MANAGE = 'manage';

    const STATUS_DELETED = 0;
    const STATUS_DRAFT = 10;
    const STATUS_PUBLISHED = 20;

    const YII_VERSION_11 = '1.1';
    const YII_VERSION_20 = '2.0';
    const YII_VERSION_30 = '3.0';

    /**
     * @var int[]
     */
    public static $availableStatusIds = [self::STATUS_DRAFT, self::STATUS_PUBLISHED];
    
    private $_description;
    /**
     * @var Image
     */
    private $_primaryImage;

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
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
            ],
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            'taggable' => [
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
            [['is_opensource', 'is_featured', 'published_to_twitter'], 'boolean'],
            [['title', 'url', 'source_url'], 'string', 'max' => 255],
            [['url', 'source_url'], 'url'],
            [['yii_version'], 'in', 'range' => array_keys(self::versions())],
            [['description', 'tagValues'], 'safe'],
            
            ['primary_image_id', 'integer'],
            ['primary_image_id', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'id', 'filter' => function (Query $query) {
                $query->andWhere(['project_id' => $this->id]);
            }],
            ['status', 'validateStatus']
        ];
    }

    public function scenarios()
    {
        static $defaultAttributes = ['title', 'url', 'is_opensource', 'source_url', 'yii_version', 'description', 'status', 'tagValues', 'primary_image_id'];

        return [
            self::SCENARIO_DEFAULT => $defaultAttributes,
            self::SCENARIO_MANAGE => array_merge($defaultAttributes, ['is_featured', 'verified']),
        ];
    }

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
            'id' => Yii::t('project', 'ID'),
            'title' => Yii::t('project', 'Title'),
            'slug' => Yii::t('project', 'Slug'),
            'url' => Yii::t('project', 'URL'),
            'is_opensource' => Yii::t('project', 'Is OpenSource'),
            'source_url' => Yii::t('project', 'Source URL'),
            'created_by' => Yii::t('project', 'Created By'),
            'updated_by' => Yii::t('project', 'Updated By'),
            'status' => Yii::t('project', 'Status'),
            'created_at' => Yii::t('project', 'Created At'),
            'updated_at' => Yii::t('project', 'Updated At'),
            'is_featured' => Yii::t('project', 'Is Featured'),
            'yii_version' => Yii::t('project', 'Yii Version'),
            'description' => Yii::t('project', 'Description in {language}', ['language' => Language::current()]),
            'tagValues' => Yii::t('project', 'Tags'),
            'primary_image_id' => Yii::t('project', 'Primary image'),
            'verified' => Yii::t('project', 'Verified'),
        ];
    }

    /**
     * @return ProjectQuery
     */
    public static function find()
    {
        return new ProjectQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['project_id' => 'id'])->inverseOf('project');
    }

    /**
     * Return sorted images. Primary image on first position.
     * 
     * @return Image[]
     */
    public function getSortedImages()
    {
        $images = ArrayHelper::index($this->images, 'id');
        if ($images) {
            $image = $images[$this->primaryImage->id];
            unset($images[$this->primaryImage->id]);
            $images = array_merge([$image], $images);
        }
        
        return $images;
    }

    public function getPlaceholderRelativeUrl()
    {
        return '/img/project_no_image.png';
    }

    public function getPlaceholderAbsoluteUrl()
    {
        return Url::to($this->getPlaceholderRelativeUrl(), true);
    }

    /**
     * @return string
     */
    public function getPrimaryImageThumbnailRelativeUrl()
    {
        if ($this->primaryImage) {
            return $this->primaryImage->getThumbnailRelativeUrl();
        }

        return $this->getPlaceholderRelativeUrl();
    }

    /**
     * @return string
     */
    public function getPrimaryImageThumbnailAbsoluteUrl()
    {
        if ($this->primaryImage) {
            return $this->primaryImage->getThumbnailAbsoluteUrl();
        }

        return $this->getPlaceholderAbsoluteUrl();
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
        return $this->hasOne(User::className(), ['id' => 'created_by'])->inverseOf('projects');
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
            self::YII_VERSION_20 => self::YII_VERSION_20,
            self::YII_VERSION_11 => self::YII_VERSION_11,
            self::YII_VERSION_30 => self::YII_VERSION_30,
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DRAFT => Yii::t('project', 'Draft'),
            self::STATUS_PUBLISHED => Yii::t('project', 'Published'),
            self::STATUS_DELETED => Yii::t('project', 'Deleted'),
        ];
    }

    /**
     * @return array
     */
    public static function getAvailableStatuses()
    {
        return array_intersect_key(self::statuses(), array_flip(static::$availableStatusIds));   
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
            ->orderBy(new Expression("language = 'en-US' DESC"))
            ->indexBy('language');
    }

    public function validateStatus()
    {
        if ($this->isAttributeChanged('status', false)) {
            if ($this->status == self::STATUS_DELETED && $this->getOldAttribute('status') != self::STATUS_DRAFT) {
                $this->addError('status', Yii::t('project', 'You can only delete a project from a draft.'));
            }
            
            if ($this->getOldAttribute('status') == self::STATUS_DELETED) {
                $this->addError('status', Yii::t('project', 'You can not restore a deleted project.'));
            }
        }
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->_description !== null) {
            $this->saveDescription($this->_description);
        }
        
        if (isset($changedAttributes['status']) && $changedAttributes['status'] != $this->status) {
            if ($this->status == self::STATUS_PUBLISHED) {
                $this->addShareJob();
            }

            if ($this->status == self::STATUS_DELETED) {
                $this->addDeleteJob();
            }
        }

        if ($insert) {
            $this->addCurrentUser();
        }
    }

    private function addCurrentUser()
    {
        $projectUser = new ProjectUser();
        $projectUser->project_id = $this->id;
        $projectUser->user_id = Yii::$app->getUser()->getId();
        return $projectUser->save();
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
     * @return bool
     */
    public function publish()
    {
        $this->status = self::STATUS_PUBLISHED;
        return $this->save();
    }

    /**
     * @return bool
     */
    public function draft()
    {
        $this->status = self::STATUS_DRAFT;
        return $this->save();
    }

    /**
     * @return bool
     */
    public function remove()
    {
        $this->status = self::STATUS_DELETED;
        return $this->save();
    }

    /**
     * @return bool
     */
    public function canPublish()
    {
        if ($this->status == self::STATUS_DRAFT) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canDraft()
    {
        if ($this->status == self::STATUS_PUBLISHED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canRemove()
    {
        if ($this->status == self::STATUS_DRAFT) {
            return true;
        }

        return false;
    }

    /**
     * @return Image|bool
     */
    public function getPrimaryImage()
    {
        if ($this->_primaryImage === null) {
            $this->_primaryImage = false;

            if ($this->primary_image_id !== null) {
                $this->_primaryImage = Image::findOne($this->primary_image_id);   
            } elseif (!empty($this->images)) {
                $this->_primaryImage = $this->images[0];
            }
        }

        return $this->_primaryImage;
    }

    /**
     * Return voting result for a project.
     * 
     * @return int
     */
    public function getVotingResult()
    {
        $value = Vote::find()
            ->andWhere([
                'project_id' => $this->id
            ])
            ->sum('value');

        return (int) $value;
    }


    /**
     * Add a task to share project. 
     * 
     * @return bool
     */
    public function addShareJob()
    {
        if ($this->status != self::STATUS_PUBLISHED || $this->published_to_twitter) {
            return false;
        }

        Yii::$app->queue->push(new ProjectShareJob([
            'projectId' => $this->id,
        ]));

        return true;
    }

    /**
     * Add a task to delete project.
     *
     * @return bool
     */
    public function addDeleteJob()
    {
        if ($this->status != self::STATUS_DELETED) {
            return false;
        }

        Yii::$app->queue->push(new ProjectDeleteJob([
            'projectId' => $this->id
        ]));

        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            ProjectDescription::deleteAll(['project_id' => $this->id]);
            ProjectTag::deleteAll(['project_id' => $this->id]);
            ProjectUser::deleteAll(['project_id' => $this->id]);
            Vote::deleteAll(['project_id' => $this->id]);
            Bookmark::deleteAll(['project_id' => $this->id]);
            
            foreach ($this->images as $image) {
                $image->delete();
            }
            Image::deleteBaseDirectories($this->id);
            
            return true;
        }
        
        return false;
    }

    /**
     * @return string
     */
    public function getObjectType()
    {
        return ClassType::PROJECT;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getUrl($params = [])
    {
        return array_merge(['project/view', 'id' => $this->id, 'slug' => $this->slug], $params);
    }

    /**
     * @inheritdoc
     */
    public function getLinkTitle()
    {
        return $this->title;
    }
    
}
