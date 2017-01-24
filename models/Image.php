<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $updatedBy
 * @property User $createdBy
 * @property Project $project
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'created_by', 'updated_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('image', 'ID'),
            'project_id' => Yii::t('image', 'Project ID'),
            'created_by' => Yii::t('image', 'Created By'),
            'updated_by' => Yii::t('image', 'Updated By'),
            'status' => Yii::t('image', 'Status'),
            'created_at' => Yii::t('image', 'Created At'),
            'updated_at' => Yii::t('image', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])->inverseOf('images');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->inverseOf('images0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id'])->inverseOf('images');
    }
}
