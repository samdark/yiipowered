<?php


namespace app\models;


use yii\base\Model;
use yii\web\UploadedFile;

class ImageUploadForm extends Model
{
    const MAX_UPLOAD_SIZE = 20000000; // 20 MB

    private $_projectID;

    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules()
    {
        return [
            [
                ['files'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'png',
                'maxSize' => self::MAX_UPLOAD_SIZE,
                'maxFiles' => 5,
            ],
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
            foreach ($this->files as $file) {
                $model = new Image();
                $model->project_id = $this->_projectID;
                if ($model->save()) {
                    $file->saveAs($model->ensureOriginalPath());
                    $model->generateFull();
                    $model->generateThumbnail();
                }
            }
            return true;
        }
        return false;
    }
}