<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%project_description}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $language
 * @property string $content
 */
class ProjectDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'content'], 'required'],
            [['project_id'], 'integer'],
            [['content'], 'string'],
            [['language'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'language' => Yii::t('app', 'Language'),
            'content' => Yii::t('app', 'Content'),
        ];
    }
}
