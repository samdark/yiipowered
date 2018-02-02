<?php

namespace app\models;

use app\components\object\ClassType;
use app\components\object\Linkable;
use app\components\queue\CommentNotificationJob;
use app\notifier\NewCommentNotification;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property string $object_type
 * @property integer $object_id
 * @property string $text
 * @property integer $status
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property-read \yii\db\ActiveRecord|Linkable $model
 * @property User $createdBy
 */
class Comment extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SCENARIO_CREATE = 'create';
    
    /**
     * @var string[] Available object types for comments.
     */
    public static $availableObjectTypes = [ClassType::PROJECT];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_type', 'object_id', 'text'], 'required'],
            [['object_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            
            ['object_type', 'string', 'max' => 255],
            ['object_type', 'in', 'range' => static::$availableObjectTypes],
            
            [['text'], 'string'],
            [['text'], 'trim'],
            
            ['status', 'in', 'range' => [self::STATUS_DELETED, self::STATUS_ACTIVE]],
            ['created_by', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['object_type', 'object_id', 'text', '!status'];
        
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_type' => 'Тип объекта',
            'object_id' => 'ID объекта',
            'text' => 'Текст',
            'status' => 'Статус',
            'created_by' => Yii::t('comment', 'Created By'),
            'created_at' => Yii::t('comment', 'Created At'),
            'updated_at' => Yii::t('comment', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(CommentQuery::class, [get_called_class()]);
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
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @return ActiveRecord|Linkable
     */
    public function getModel()
    {
        if (!in_array($this->object_type, static::$availableObjectTypes, true)) {
            return null;
        }

        /** @var ActiveRecord $modelClass */
        $modelClass = ClassType::getClass($this->object_type);
        return $modelClass::findOne($this->object_id);
    }

    /**
     * @return bool
     */
    public function addNotificationJob()
    {
        if ($this->object_type === ClassType::PROJECT) {
            /** @var Project $project */
            $project = $this->model;
            
            foreach ($project->users as $user) {
                if ((new NewCommentNotification($this, $user))->isAllowSendToEmail()) {
                    Yii::$app->queue->push(new CommentNotificationJob([
                        'commentId' => $this->id,
                        'recipientId' => $user->id,
                    ]));   
                }
            }
            
            return true;
                
        }
        
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if ($insert) {
           $this->addNotificationJob();
        }
    }
}
