<?php
namespace app\models;

use app\components\UserPermissions;
use yii\db\ActiveQuery;

class ProjectQuery extends ActiveQuery
{
    /**
     * @param bool $value
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

        $parts = ['status = :status'];
        $params = ['status' => Project::STATUS_PUBLISHED];

        if (\Yii::$app->user->id) {
            $this->leftJoin('project_user pu', 'pu.project_id = project.id');
            $parts[] = 'pu.user_id = :userid';
            $params['userid'] = \Yii::$app->user->id;
        }
        return $this->andWhere(implode(' OR ', $parts), $params);
    }
}
