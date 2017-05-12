<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

$this->title = Yii::t('app', 'Request password reset');
?>

<div class="site-request-password-reset">
    <div class="form-wrapper">
        <h2><?= Html::encode($this->title) ?></h2>

        <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <?= $form->field($model, 'email')
            ->textInput(['placeholder' => $model->getAttributeLabel('email')])
            ->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
