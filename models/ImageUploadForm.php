<?php


namespace app\models;

use yii\base\Model;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * @property Image $image
 * @property bool|array $imageCropDataAsArray
 */
class ImageUploadForm extends Model
{
    const MAX_UPLOAD_SIZE = 20000000; // 20 MB

    private $_projectID;
    /**
     * @var Image
     */
    private $_image;
    
    /**
     * @var array
     */
    private $_imageCropDataAsArray;

    /**
     * @var UploadedFile
     */
    public $file;
    /**
     * @var string
     */
    public $imageCropData;
    /**
     * @var int
     */
    public $imageId;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => \Yii::t('project', 'Upload an image'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['file'],
                'image',
                'skipOnEmpty' => true,
                'extensions' => 'png',
                'maxSize' => self::MAX_UPLOAD_SIZE
            ],

            ['imageCropData', 'required'],
            ['imageCropData', function ($attribute) {
                if (!$this->imageCropDataAsArray) {
                    $this->addError($attribute, 'Empty crop date for thumb.');
                }
            }],

            ['imageId', 'default', 'value' => null],
            ['imageId', 'integer'],
            ['imageId', function () {
                if (!$this->image) {
                    $this->addError('image', 'Image not found.');
                }
            }],
        ];
    }

    public function __construct($projectID, array $config = [])
    {
        $this->_projectID = $projectID;
        parent::__construct($config);
    }

    /**
     * @return bool
     */
    public function upload()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->file !== null) {
            $image = new Image();
            $image->project_id = $this->_projectID;

            if ($image->save()) {
                $this->file->saveAs($image->ensureOriginalPath());
                $image->generateFull();
                $image->generateThumbnail($this->imageCropDataAsArray ? : null);

                return true;
            }
        } elseif ($this->image) {
            $image = $this->image;
            $image->generateThumbnail($this->imageCropDataAsArray ? : null);
            if ($image->save()) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * @return bool|array
     */
    public function getImageCropDataAsArray()
    {
        if ($this->_imageCropDataAsArray === null) {
            $this->_imageCropDataAsArray = false;
            
            try {
                $imageCropData = Json::decode($this->imageCropData);
                if (!array_diff(['width', 'height', 'x', 'y'], array_keys($imageCropData))) {
                    $this->_imageCropDataAsArray = $imageCropData;
                }
            } catch (\Exception $ex) {

            }   
        }
        
        return $this->_imageCropDataAsArray;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        if ($this->imageId !== null && $this->_image === null) {
            /** @var Image $image */
            $image = Image::find()
                ->andWhere([
                    'id' => $this->imageId,
                    'project_id' => $this->_projectID
                ])
                ->limit(1)
                ->one();

            $this->_image = $image ? : false;
        }

        return $this->_image;
    }
}