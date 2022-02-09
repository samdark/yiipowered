<?php

namespace app\commands;

use app\checkers\CheckerService;
use app\components\queue\ProjectCheckJob;
use app\components\queue\ProjectDeleteJob;
use app\models\Project;
use Yii;
use yii\console\Controller;
use yii\queue\Queue;

final class CheckController extends Controller
{
    public function actionUrl(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->stderr("Invalid URL.\n");
            return;
        }

        /** @var CheckerService $checker */
        $checker = Yii::$app->checker;
        $result = $checker->check($url);

        $this->stdout(sprintf("It is %s because:\n", $result->getResult()));
        $this->stdout('- ' . implode("\n- ", $result->getReasons()) . "\n");
    }

    public function actionProject(string $id)
    {
        /** @var Project $project */
        $project = Project::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();

        if ($project === null) {
            $this->stderr("There is no such project.\n");
        }

        /** @var Queue $queue */
        $queue = Yii::$app->queue;
        $queue->push(new ProjectCheckJob([
            'projectId' => $id
        ]));
    }

    public function actionAll()
    {
        $projectIds = Project::find()
            ->select('id')
            ->notChecked()
            ->verified(false)
            ->column();

        /** @var Queue $queue */
        $queue = Yii::$app->queue;
        foreach ($projectIds as $projectId) {
            $queue->push(new ProjectCheckJob([
                'projectId' => $projectId
            ]));
        }
    }
}