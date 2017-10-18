<?php
namespace app\models;

use app\components\UserPermissions;
use creocoder\taggable\TaggableQueryBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * ProjectQuery
 *
 * @method anyTagValues($values, $attribute = null)
 * @method allTagValues($values, $attribute = null)
 * @method relatedByTagValues($values, $attribute = null)
 *
 * @see Project
 */
class ProjectQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }

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
            return $this->available();
        }

        if (Yii::$app->user->id) {
            $this->leftJoin(['pu' => 'project_user'], 'pu.project_id = project.id')
                ->andWhere([
                    'OR',
                    ['status' => Project::STATUS_PUBLISHED],
                    [
                        'pu.user_id' => Yii::$app->user->id,
                        'status' => Project::$availableStatusIds
                    ]
                ]);    
        } else {
            $this->published();
        }
   
        return $this;
    }

    /**
     * @return $this
     */
    public function freshFirst()
    {
        return $this->orderBy('created_at DESC');
    }

    /**
     * @return $this
     */
    public function published()
    {
        return $this->andWhere(['status' => Project::STATUS_PUBLISHED]);
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        return $this->andWhere(['status' => Project::STATUS_DELETED]);
    }
    
    /**
     * @return $this
     */
    public function available()
    {
        return $this->andWhere(['status' => Project::$availableStatusIds]);
    }

    /**
     * @param User $user
     * @return $this
     */
    public function hasUser(User $user)
    {
        $this->leftJoin('project_user pu', 'pu.project_id = project.id');
        return $this->andWhere(['pu.user_id' => $user->id]);
    }
}
