<?php

namespace app\components\queue;

use app\checkers\CheckerResult;
use app\checkers\CheckerService;
use app\models\Project;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

final class ProjectCheckJob extends BaseObject implements JobInterface
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
            ->verified(false)
            ->notChecked()
            ->limit(1)
            ->one();

        if ($project === null) {
            Yii::error("Skipping checking of $project->url.\n");
            return;
        }

        /** @var CheckerService $checker */
        $checker = Yii::$app->checker;

        $result = $checker->check($project->url);
        switch ($result->getResult()) {
            case CheckerResult::YII:
                $project->verified = true;
                break;
            case CheckerResult::YII_1_1:
                $project->yii_version = Project::YII_VERSION_11;
                $project->verified = true;
                break;
            case CheckerResult::YII_2_0:
                $project->yii_version = Project::YII_VERSION_20;
                $project->verified = true;
                break;
            case CheckerResult::YII_3_0:
                $project->yii_version = Project::YII_VERSION_30;
                $project->verified = true;
                break;
            case CheckerResult::NOT_YII:
                $project->verified = false;
                $project->status = Project::STATUS_DELETED;
                break;
            case CheckerResult::UNCERTAIN:
            case CheckerResult::CONFLICT:
                break;
        }

        $project->check_result = $result->getResult();
        $project->check_log = implode("\n", $result->getReasons());
        if (!$project->save(false)) {
            Yii::error("Failed to save checked project $project->id.");
        }
    }
}