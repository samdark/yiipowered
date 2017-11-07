<?php

namespace app\models;

use app\components\object\ObjectIdentityInterface;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Comment]].
 *
 * @see Comment
 */
class CommentQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => Comment::STATUS_ACTIVE]);
    }

    /**
     * @param ObjectIdentityInterface $object
     *
     * @return $this
     */
    public function forObject(ObjectIdentityInterface $object)
    {
        return $this->andWhere(['object_type' => $object->getObjectType(), 'object_id' => $object->getObjectId()]);
    }
}
