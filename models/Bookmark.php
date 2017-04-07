<?php

namespace app\models;

use Yii;
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
    public function rules()
    {
        return [
            [['user_id', 'project_id'], 'required'],
            [['user_id', 'project_id', 'created_at'], 'integer'],

            [['project_id'], 'exist', 'targetClass' => Project::className(), 'targetAttribute' => 'id'],
            [['user_id'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id'],
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null
            ]
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id'])->inverseOf('stars');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('stars');
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
    
    /**
     * Add or remove bookmark for pair the project and user.
     * 
     * @param $projectId
     * @param $userId
     * @param bool $state If true then add bookmark else delete bookmark
     *
     * @return bool
     */
    public static function changeState($projectId, $userId, $state)
    {
        /** @var Bookmark $bookmark */
        $bookmark = static::findOne([
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);

        if ($bookmark && !$state) {
            return $bookmark->delete();
        } elseif (!$bookmark && $state) {
            $bookmark = new static();
            $bookmark->project_id = $projectId;
            $bookmark->user_id = $userId;
            return $bookmark->save();
        }
        
        return true;
    }
}
