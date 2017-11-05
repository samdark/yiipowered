<?php
/**
 * @var Comment $commentForm
 * @var ActiveForm $form
 */

use app\assets\MarkdownEditorAsset;
use app\models\Comment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

MarkdownEditorAsset::register($this);

?>

<div class="row">
    <div class="col-md-offset-2 col-md-9">
        <h3><?= Yii::t('comment', 'Leave a comment') ?></h3>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-2 col-md-9">
        <?php if ($commentForm): ?>
            <?php $form = ActiveForm::begin() ?>
            <?= $form->field($commentForm, 'text')->label(false)->textarea(['class' => 'markdown-editor']) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('comment', 'Comment'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end() ?>
        <?php else: ?>
            <p>
                <?= Yii::t('comment', '{loginLink} or {signupLink} to post comments.', [
                    'loginLink' => Html::a(Yii::t('comment', 'Login'), ['/site/login']),
                    'signupLink' => Html::a(Yii::t('comment', 'signup'), ['/site/signup'])
                ]) ?>
            </p>
        <?php endif ?>
    </div>
</div>
