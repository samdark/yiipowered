<?php

namespace app\models;

use Imagine\Image\ImageInterface;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\imagine\Image as ImagineImage;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $updatedBy
 * @property User $createdBy
 * @property Project $project
 */
class Image extends \yii\db\ActiveRecord
{
    const SIZE_FULL = [804, 528];
    const SIZE_THUMBNAIL = [402, 264];

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
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'integer'],
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

    public function getUrl()
    {
        return Yii::getAlias('@web/img/project/' . $this->project_id . '/' . $this->getFullFilename());
    }

    public function getThumbnailUrl()
    {
        return Yii::getAlias('@web/img/project/' . $this->project_id . '/' . $this->getThumbnailFilename());
    }

    public function getOriginalFilename()
    {
        return $this->id . '.png';
    }

    public function getFullFilename()
    {
        return $this->id . '_full.png';
    }

    public function getThumbnailFilename()
    {
        return $this->id . '_thm.png';
    }

    public function getOriginalPath()
    {
        return Yii::getAlias('@app/images') . '/' . $this->project_id . '/' . $this->getOriginalFilename();
    }

    public function ensureOriginalPath()
    {
        $path = $this->getOriginalPath();
        FileHelper::createDirectory(dirname($path));
        return $path;
    }

    public function getFullPath()
    {
        return Yii::getAlias('@webroot/img/project/') . '/' . $this->project_id . '/' . $this->getFullFilename();
    }

    public function ensureFullPath()
    {
        $path = $this->getFullPath();
        FileHelper::createDirectory(dirname($path));
        return $path;
    }

    public function getThumbnailPath()
    {
        return Yii::getAlias('@webroot/img/project/') . '/' . $this->project_id . '/' . $this->getThumbnailFilename();
    }

    public function ensureThumbnailPath()
    {
        $path = $this->getThumbnailPath();
        FileHelper::createDirectory(dirname($path));
        return $path;
    }

    public function generateThumbnail()
    {
        ImagineImage::$thumbnailBackgroundAlpha = 0;

        $size = self::SIZE_THUMBNAIL;
        ImagineImage::thumbnail($this->getOriginalPath(), $size[0], $size[1], ImageInterface::THUMBNAIL_INSET)
            ->save($this->ensureThumbnailPath());
    }

    public function generateFull()
    {
        ImagineImage::$thumbnailBackgroundAlpha = 0;

        $size = self::SIZE_FULL;
        ImagineImage::thumbnail($this->getOriginalPath(), $size[0], $size[1], ImageInterface::THUMBNAIL_INSET)
            ->save($this->ensureFullPath());
    }

    public function afterDelete()
    {
        parent::afterDelete();

        unlink($this->getThumbnailPath());
        unlink($this->getFullPath());
        unlink($this->getOriginalPath());
    }
}
