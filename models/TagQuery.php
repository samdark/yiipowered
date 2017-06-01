<?php
namespace app\models;

use yii\db\ActiveQuery;

/**
 * TagQuery
 *
 * @see Tag
 */
class TagQuery extends ActiveQuery
{
    public function top($count = 10)
    {
        $this->orderBy('frequency desc');
        $this->indexBy('frequency');
        $this->limit($count);

        return $this;
    }
}
