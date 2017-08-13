<?php

namespace app\components\queue;

use Abraham\TwitterOAuth\TwitterOAuth;
use app\models\Project;
use Yii;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\queue\closure\Job;

class ProjectShareJob extends Job 
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
        
        if (!$project->published_to_twitter) {
            $params = Yii::$app->params;
            
            $twitter = new TwitterOAuth(
                $params['twitter.consumerKey'],
                $params['twitter.consumerSecret'],
                $params['twitter.accessToken'],
                $params['twitter.accessTokenSecret']
            );

            $projectUrl = Url::to(['project/view', 'id' => $project->id, 'slug' => $project->slug], true);

            //NOTE: The maximum message length is 140 characters. For url you need 23 characters.
            $message = StringHelper::truncate($project->title, 108) . " {$projectUrl} #yii";
            $twitter->post('statuses/update', ['status' => $message]);
            if ($twitter->getLastHttpCode() == 200) {
                $project->published_to_twitter = true;
                $project->save();
            }
        }
    }
}