<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%vote}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $project_id
 * @property integer $value
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Project $project
 * @property User $user
 */
class Vote extends ActiveRecord
{
    const VALUE_UP = 1;
    const VALUE_DOWN = -1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vote}}';
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
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'value'], 'required'],
            [['value'], 'in', 'range' => [self::VALUE_DOWN, self::VALUE_UP]],
            [['project_id'], 'integer'],
            [['user_id', 'project_id'], 'unique', 'targetAttribute' => ['user_id', 'project_id'], 'message' => 'The combination of User ID and Project ID has already been taken.'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vote', 'ID'),
            'user_id' => Yii::t('vote', 'User ID'),
            'project_id' => Yii::t('vote', 'Project ID'),
            'value' => Yii::t('vote', 'Value'),
            'created_at' => Yii::t('vote', 'Created At'),
            'updated_at' => Yii::t('vote', 'Updated At'),
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
     * Return a vote for pair the project and user.
     * 
     * @param int $projectId
     * @param int $userId
     *
     * @return Vote|null
     */
    public static function getVote($projectId, $userId)
    {
        return static::findOne([
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);
    }
}
