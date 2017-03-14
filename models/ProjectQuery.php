<?php

namespace app\models;

use app\components\UserPermissions;
use creocoder\taggable\TaggableQueryBehavior;
use yii\db\ActiveQuery;

/**
 * ProjectQuery
 *
 * @method anyTagValues($values, $attribute = null)
 * @method allTagValues($values, $attribute = null)
 * @method relatedByTagValues($values, $attribute = null)
 */
class ProjectQuery extends ActiveQuery
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function featured($value = true)
    {
        return $this->andWhere(['is_featured' => $value]);
    }

    /**
     * @return $this
     */
    public function publishedOrEditable()
    {
        // no extra conditions for users able to manage all projects
        if (UserPermissions::canManageProjects()) {
            return $this;
        }

        $parts  = ['status = :status'];
        $params = ['status' => Project::STATUS_PUBLISHED];

        if (\Yii::$app->user->id) {
            $this->leftJoin('project_user pu', 'pu.project_id = project.id');
            $parts[]          = 'pu.user_id = :userid';
            $params['userid'] = \Yii::$app->user->id;
        }

        return $this->andWhere(implode(' OR ', $parts), $params);
    }
}
