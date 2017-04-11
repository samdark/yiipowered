<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%star}}".
 *
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $star
 * @property integer $created_at
 * @property integer $updated_at
 */
class Star extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%star}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id'], 'required'],
            [['user_id', 'project_id', 'star', 'created_at', 'updated_at'], 'integer'],

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
            'star' => 'Star',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [TimestampBehavior::className()];
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
     * @param int $projectId
     * @param int $userId
     *
     * @return int
     */
    public static function getStarValue($projectId, $userId)
    {
        $star = static::find()
            ->select('star')
            ->andWhere([
                'project_id' => $projectId,
                'user_id' => $userId,
            ])
            ->scalar();
        
        return $star ?? 0;
    }
    
    /**
     * @param $projectId
     * @param $userId
     * @param int $starValue
     *
     * @return int
     */
    public static function changeStar($projectId, $userId, $starValue = -1)
    {
        /** @var Star $star */
        $star = static::findOne([
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);
        
        if (!$star) {
            $star = new static();
            $star->project_id = $projectId;
            $star->user_id = $userId;
            $star->star = 1;
    
            $star->save();
        } else {
            if ($starValue == -1) {
                $star->star = $star->star == 0 ? 1 : 0;
                $star->save(false);
            } elseif ($star->star != $starValue) {
                $star->star = $starValue;
                $star->save(false);    
            }
        }
        
        return $star->star;
    }
}
