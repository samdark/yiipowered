<?php

namespace app\components\queue;

use Yii;
use app\models\Project;
use yii\queue\closure\Job;

class ProjectDeleteJob extends Job 
{
    /**
     * @var int
     */
    public $projectId;

    public function execute($queue)
    {
        /** @var Project $project */
        $project = Project::find()
            ->andWhere(['id' => $this->projectId])
            ->deleted()
            ->limit(1)
            ->one();

        if (!$project) {
            return;
        }
        
        if (!$project->delete()) {
            Yii::error("Failed delete project $project->id.");
        }
    }
}
