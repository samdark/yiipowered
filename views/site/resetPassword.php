<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

$this->title = Yii::t('app', 'Reset password');
?>
<div class="site-reset-password">
    <div class="form-wrapper">
        <h2><?= Html::encode($this->title) ?></h2>

        <p><?= Yii::t('app', 'Please choose your new password:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <?= $form->field($model, 'password')
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
            ->label(false) ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
