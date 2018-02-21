<?php
/**
 * @var Comment[] $comments
 */

use app\components\UserPermissions;
use app\models\Comment;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;

?>

<?php if ($comments): ?>
    <div class="row">
        <div class="col-md-offset-2 col-md-9">
            <h3><?= Yii::t('comment', 'User contributed notes') ?> <sup class="badge"><?= count($comments) ?></sup></h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-offset-2 col-md-9">
            <div class="comments">
                <?php foreach ($comments as $comment): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="comment">
                                <div class="header">
                                    <div class="row" id="c<?= $comment->id ?>">
                                        <div class="col-md-1">
                                            <a href="#c<?= $comment->id ?>" class="id">#<?= $comment->id ?></a>
                                        </div>
                                        <div class="col-md-9 details">
                                            <span class="username"><?= Html::encode($comment->createdBy->username) ?></span>
                                            <?= Yii::t('comment', 'at {time}', [
                                                'time' => Yii::$app->formatter->asDatetime($comment->created_at)
                                            ]) ?>
                                        </div>

                                        <?php if (UserPermissions::canManageComments()): ?>
                                            <div class="col-md-2">
                                                <?= Html::a(
                                                    '<i class="fa fa-remove"></i> ' . Yii::t('comment', 'Delete'),
                                                    ['/project/delete-comment', 'id' => $comment->id], [
                                                    'data-method' => 'post',
                                                    'data-confirm' => Yii::t('comment', 'Are you sure you want to delete this comment?'),
                                                    'class' => 'btn btn-danger'
                                                ]) ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="text">
                                        <?= HtmlPurifier::process(Markdown::process($comment->text, 'gfm'), Yii::$app->params['HtmlPurifier.projectComment']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?php endif ?>
