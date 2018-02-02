<?php

namespace app\components\queue;

use Yii;
use app\models\User;
use app\notifier\NewCommentNotification;
use app\notifier\Notifier;
use app\models\Comment;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

/**
 *
 * @property int $ttr
 */
class CommentNotificationJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var int
     */
    public $commentId;
    /**
     * @var int
     */
    public $recipientId;

    public function execute($queue)
    {
        $comment = Comment::find()        
            ->andWhere(['id' => $this->commentId])
            ->limit(1)
            ->one();

        if (!$comment) {
            return false;
        }

        $recipient = User::find()
            ->andWhere(['id' => $this->recipientId])
            ->limit(1)
            ->one();

        if (!$recipient) {
            return false;
        }
        
        $notifier = new Notifier(new NewCommentNotification($comment, $recipient));
        $notifier->sendEmails();
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 300;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
    
}
