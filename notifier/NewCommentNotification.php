<?php

namespace app\notifier;

use app\models\User;
use app\models\Comment;
use Yii;
use yii\helpers\Markdown;
use yii\helpers\Url;

class NewCommentNotification implements NotificationInterface
{
    /**
     * @var Comment 
     */
    private $_comment;
    /**
     * @var User
     */
    private $_recipient;

    /**
     * NewCommentNotification constructor.
     *
     * @param Comment $comment
     * @param User $recipient
     */
    public function __construct(Comment $comment, User $recipient)
    {
        $this->_comment = $comment;
        $this->_recipient = $recipient;
    }

    /**
     * @return User
     */
    public function getToUser()
    {
        return $this->_recipient;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return 'New comment was added at YiiPowered';
    }

    /**
     * @return string
     */
    public function getText()
    {
        $url = Url::to($this->_comment->model->getUrl(['#' => "c{$this->_comment->id}"]), true);

        $message = "New comment was added: {$url}'.\n\n" .
            "Written by {$this->_recipient->username} at " . Yii::$app->formatter->asDatetime($this->_comment->created_at) . "\n\n" .
            wordwrap(strip_tags(Markdown::process($this->_comment->text, 'gfm')));
        
        return $message;
    }

    /**
     * @inheritdoc
     */
    public function isAllowSendToEmail()
    {
        if ((int) $this->_comment->created_by !== (int) $this->_recipient->id && $this->_recipient->notify_about_comment_on_email) {
            return true;
        }
        
        return false;
    }
    
}
