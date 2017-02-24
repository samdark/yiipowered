<?php


namespace app\notifier;


use app\models\Project;
use app\models\User;
use yii\helpers\Url;

class NewProjectNotification implements NotificationInterface
{
    private $project;

    /**
     * NewProjectNotification constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return User
     */
    public function getToUser()
    {
        return User::findByUsername('samdark');
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return 'New project at YiiPowered!';
    }

    /**
     * @return string
     */
    public function getText()
    {
        $link = Url::to(['project/view', 'id' => $this->project->id, 'slug' => $this->project->slug], true);

        return <<<TEXT
There's a new project at YiiPowered:

$link
TEXT;

    }
}
