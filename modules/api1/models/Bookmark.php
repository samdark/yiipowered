<?php
namespace app\modules\api1\models;

class Bookmark extends \app\models\Bookmark
{
    public function extraFields()
    {
        return [
            'project',
        ];
    }

    public function fields()
    {
        return [
            'createdAt' => 'created_at',
        ];
    }
}