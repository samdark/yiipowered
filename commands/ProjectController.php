<?php

namespace app\commands;

use Yii;
use app\models\Project;
use yii\console\Controller;

class ProjectController extends Controller
{
    /**
     * Add a task to share projects.
     * 
     * NOTE: For new projects tasks are created automatically.
     */
    public function actionAddShareJobs()
    {
        $queryProjects = Project::find()
            ->published()
            ->andWhere([
                'published_to_twitter' => false,
            ])
            ->orderBy(['created_by' => SORT_ASC]);
        
        $current = 0;
        /** @var Project $project */
        foreach ($queryProjects->each(100) as $project) {
            $current++;
            $this->stdout("[{$current}] project: id = {$project->id}\n");
            
            $project->addShareJob();
        }
    }

    /**
     * Adds jobs that clean up deleted projects.
     *
     * NOTE: When a project is marked for deletion the task is created automatically.
     */
    public function actionAddDeleteJobs()
    {
        $queryProjects = Project::find()
            ->deleted()
            ->orderBy(['created_by' => SORT_ASC]);

        $current = 0;
        /** @var Project $project */
        foreach ($queryProjects->each(100) as $project) {
            $current++;
            $this->stdout("[{$current}] project: id = {$project->id}\n");

            $project->addDeleteJob();
        }
    }
}
