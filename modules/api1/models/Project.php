<?php
namespace app\modules\api1\models;

use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

/**
 * @property Image $primaryImage
 */
class Project extends \app\models\Project implements Linkable
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['taggable']['tagValuesAsArray'] = true;
        return $behaviors;
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'url' => 'url',
            'sourceUrl' => 'source_url',
            'isOpenSource' => 'is_opensource',
            'isFeatured' => 'is_featured',
            'yiiVersion' => 'yii_version',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'tags' => 'tagValues',
            'description' => 'description',
            'thumbnail' => 'primaryImageThumbnailAbsoluteUrl',
        ];
    }

    public function extraFields()
    {
        return [
            'users',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%project_user}}', ['project_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['/project/view', 'id' => $this->id, 'slug' => $this->slug], 'http'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'primary_image_id']);
    }
    
}
