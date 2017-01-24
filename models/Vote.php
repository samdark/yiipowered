<?php

namespace app\models;

use Yii;

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
class Vote extends \yii\db\ActiveRecord
{
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
    public function rules()
    {
        return [
            [['user_id', 'project_id', 'value', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'project_id', 'value', 'created_at', 'updated_at'], 'integer'],
            [['user_id', 'project_id'], 'unique', 'targetAttribute' => ['user_id', 'project_id'], 'message' => 'The combination of User ID and Project ID has already been taken.'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
        return $this->hasOne(Project::className(), ['id' => 'project_id'])->inverseOf('votes');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('votes');
    }
}
