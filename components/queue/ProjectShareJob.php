<?php

namespace app\components\queue;

use Abraham\TwitterOAuth\TwitterOAuth;
use app\models\Project;
use Yii;
use yii\base\BaseObject;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\queue\JobInterface;

class ProjectShareJob extends BaseObject implements JobInterface
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
            ->published()
            ->limit(1)
            ->one();

        if (!$project) {
            return;
        }

        if ($project->published_to_twitter) {
            return;
        }

        $params = Yii::$app->params;

        $twitter = new TwitterOAuth(
            $params['twitter.consumerKey'],
            $params['twitter.consumerSecret'],
            $params['twitter.accessToken'],
            $params['twitter.accessTokenSecret']
        );

        $projectUrl = Url::to([
            'project/view',
            'id' => $project->id,
            'slug' => $project->slug
        ], true);

        // The maximum message length is 140 characters. For URL you need 23 characters.
        $message = StringHelper::truncate($project->title, 108) . " {$projectUrl} #yii";
        $twitter->post('statuses/update', ['status' => $message]);

        $status = (int) $twitter->getLastHttpCode();
        if ($status === 200) {
            $project->published_to_twitter = true;
            if (!$project->save()) {
                Yii::error("Failed marking project $project->id as published_to_twitter.");
            }
        } else {
            Yii::error("Tweeting failed with status $status:\n" . var_export($twitter->getLastBody(), true));
        }
    }
}
