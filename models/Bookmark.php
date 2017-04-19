<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%bookmark}}".
 *
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $created_at
 * 
 * @property User $user
 * @property Project $project
 */
class Bookmark extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bookmark}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
            [['project_id'], 'integer'],

            [['project_id'], 'exist', 'targetClass' => Project::className(), 'targetAttribute' => 'id'],
            [['project_id', 'user_id'], 'unique', 'targetAttribute' => [ 'project_id', 'user_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project',
            'user_id' => 'User',
            'created_at' => 'Created At',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Check exists a bookmark for pair the project and user.
     * 
     * @param int $projectId
     * @param int $userId
     *
     * @return bool
     */
    public static function exists($projectId, $userId)
    {
        return static::find()
            ->andWhere([
                'project_id' => $projectId,
                'user_id' => $userId,
            ])
            ->exists();
    }
}
