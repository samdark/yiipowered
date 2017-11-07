<?php
/**
 * @var Comment $commentForm
 * @var Comment[] $comments
 * @var ActiveForm $form
 * @var View $this
 */

use app\models\Comment;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<div class="comment-wrapper">
    <div class="container">
        <?= $this->render('_list', ['comments' => $comments]) ?>
        <?= $this->render('_form', ['commentForm' => $commentForm]) ?>
    </div>
</div>
