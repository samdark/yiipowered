<?php

namespace app\models;

use claviska\SimpleImage;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\helpers\Url;

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
 *
 * Timestamp behavior:
 *
 * @method touch($attribute)
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
            [
                ['project_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Project::className(),
                'targetAttribute' => ['project_id' => 'id'],
            ],
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
        return Yii::getAlias('@web/img/project/' . $this->project_id . '/' . $this->getFullFilename()) . '?' . $this->updated_at;
    }

    public function getThumbnailRelativeUrl()
    {
        return Yii::getAlias('@web/img/project/' . $this->project_id . '/' . $this->getThumbnailFilename()) . '?' . $this->updated_at;
    }

    public function getBigThumbnailRelativeUrl()
    {
        return Yii::getAlias('@web/img/project/' . $this->project_id . '/' . $this->getBigThumbnailFilename()) . '?' . $this->updated_at;
    }

    public function getThumbnailAbsoluteUrl()
    {
        return Url::to($this->getThumbnailRelativeUrl(), 'http');
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

    public function getBigThumbnailFilename()
    {
        return $this->id . '_big_thm.png';
    }

    public function getOriginalPath()
    {
        return Yii::getAlias('@app/images/') . $this->project_id . '/' . $this->getOriginalFilename();
    }

    public function ensureOriginalPath()
    {
        $path = $this->getOriginalPath();
        FileHelper::createDirectory(dirname($path));

        return $path;
    }

    public function getFullPath()
    {
        return Yii::getAlias('@webroot/img/project/') . $this->project_id . '/' . $this->getFullFilename();
    }

    public function ensureFullPath()
    {
        $path = $this->getFullPath();
        FileHelper::createDirectory(dirname($path));

        return $path;
    }

    public function getThumbnailPath()
    {
        return Yii::getAlias('@webroot/img/project/') . $this->project_id . '/' . $this->getThumbnailFilename();
    }

    private function getBigThumbnailPath()
    {
        return Yii::getAlias('@webroot/img/project/') . $this->project_id . '/' . $this->getBigThumbnailFilename();
    }


    public function ensureThumbnailPath()
    {
        $path = $this->getThumbnailPath();
        FileHelper::createDirectory(dirname($path));

        return $path;
    }

    private function ensureBigThumbnailPath()
    {
        $path = $this->getBigThumbnailPath();
        FileHelper::createDirectory(dirname($path));

        return $path;
    }

    /**
     * @param array|null $crop
     */
    public function generateThumbnail($crop = null)
    {
        $size = Yii::$app->params['image.size.thumbnail'];

        $image = new SimpleImage($this->getOriginalPath());
        if ($crop !== null) {
            $image->crop($crop['x'], $crop['y'], $crop['width'] + $crop['x'], $crop['height'] + $crop['y']);
        }

        $image
            ->resize($size[0])
            ->crop(0, 0, $size[0], $size[1])
            ->toFile($this->ensureThumbnailPath());

        $this->touch('updated_at');
    }


    public function generateBigThumbnail($crop = null)
    {
        $size = Yii::$app->params['image.size.big_thumbnail'];

        $image = new SimpleImage($this->getOriginalPath());
        if ($crop !== null) {
            $image->crop($crop['x'], $crop['y'], $crop['width'] + $crop['x'], $crop['height'] + $crop['y']);
        }

        $image
            ->resize($size[0])
            ->crop(0, 0, $size[0], $size[1])
            ->toFile($this->ensureBigThumbnailPath());

        $this->touch('updated_at');
    }

    public function generateFull()
    {
        $size = Yii::$app->params['image.size.full'];

        (new SimpleImage($this->getOriginalPath()))
            ->bestFit($size[0], $size[1])
            ->toFile($this->ensureFullPath());

        $this->touch('updated_at');
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $this->removeFile($this->getThumbnailPath());
        $this->removeFile($this->getBigThumbnailPath());
        $this->removeFile($this->getFullPath());
        $this->removeFile($this->getOriginalPath());
    }

    private function removeFile($path)
    {
        if (!file_exists($path)) {
            return true;
        }

        return unlink($path);
    }
}
