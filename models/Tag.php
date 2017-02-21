<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $description
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProjectTag[] $projectTags
 * @property Project[] $projects
 */
class Tag extends \yii\db\ActiveRecord
{
    const TYPE_USER = 10;
    const TYPE_TECH = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['type'], 'integer'],
            [['name', 'icon'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('tag', 'ID'),
            'name' => Yii::t('tag', 'Name'),
            'icon' => Yii::t('tag', 'Icon'),
            'description' => Yii::t('tag', 'Description'),
            'type' => Yii::t('tag', 'Type'),
            'created_at' => Yii::t('tag', 'Created At'),
            'updated_at' => Yii::t('tag', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTags()
    {
        return $this->hasMany(ProjectTag::className(), ['tag_id' => 'id'])->inverseOf('tag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'project_id'])->viaTable('{{%project_tag}}', ['tag_id' => 'id']);
    }
}
