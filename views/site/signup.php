<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

$this->title = Yii::t('app', 'Signup');
?>
<div class="site-login">
    <div class="form-wrapper">
        <div class="login-box">
            <h2><?= Html::encode($this->title) ?></h2>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username')
                    ->textInput(['placeholder' => $model->getAttributeLabel('username')])
                    ->label(false) ?>
                <?= $form->field($model, 'email')
                    ->textInput(['placeholder' => $model->getAttributeLabel('email')])
                    ->label(false) ?>
                <?= $form->field($model, 'password')
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
                    ->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'signup-button', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="divider">
            <?= Yii::t('app', 'or') ?>
        </div>

        <div class="oauth-box">
            <?= \app\widgets\AuthChoise::widget([
                'options' => ['class' => 'auth-clients-wrapper'],
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => false,
            ]) ?>
        </div>
</div>
