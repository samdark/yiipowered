<?php
namespace app\models;

use app\components\UserPermissions;
use creocoder\taggable\TaggableQueryBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * TagQuery
 */
class TagQuery extends ActiveQuery
{
    public function top($count = 10)
    {
        $this->orderBy('frequency desc');
        $this->indexBy('frequency');


        return $this;
    }
}
