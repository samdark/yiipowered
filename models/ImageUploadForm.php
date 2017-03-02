<?php


namespace app\models;

use yii\base\Model;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * @property bool|array $imageCropDataAsArray
 */
class ImageUploadForm extends Model
{
    const MAX_UPLOAD_SIZE = 20000000; // 20 MB

    private $_projectID;
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

    public function rules()
    {
        return [
            [
                ['file'],
                'image',
                'skipOnEmpty' => false,
                'extensions' => 'png',
                'maxSize' => self::MAX_UPLOAD_SIZE
            ],
            
            ['imageCropData', 'required'],
            ['imageCropData', function ($attribute) {
                if (!$this->imageCropDataAsArray) {
                    $this->addError($attribute, 'Empty crop date for thumb.');
                }
            }],
        ];
    }

    public function __construct($projectID, array $config = [])
    {
        $this->_projectID = $projectID;
        parent::__construct($config);
    }

    public function upload()
    {
        if ($this->validate()) {
            if ($this->file !== null) {
                $model = new Image();
                $model->project_id = $this->_projectID;
                if ($model->save()) {
                    $this->file->saveAs($model->ensureOriginalPath());
                    $model->generateFull();
                    $model->generateThumbnail($this->imageCropDataAsArray ?: null);
                }
            }

            return true;
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
}