<?php

namespace app\modules\api1\models;

class Image extends \app\models\Image
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    public function extraFields()
    {
        return [];
    }

    public function fields()
    {
        return [
            'id',
            'thumbnailAbsoluteUrl',
            'updatedAt' => 'updated_at'
        ];
    }
}
