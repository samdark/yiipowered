<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = Yii::t('app', 'Login');
?>
<div class="site-login">
    <div class="form-wrapper">
        <div class="login-box">
            <h2><?= Yii::t('app', 'Login') ?></h2>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username')
                ->textInput(['placeholder' => $model->getAttributeLabel('username')])
                ->label(false) ?>
            <?= $form->field($model, 'password')
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
                ->label(false) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'login-button', 'name' => 'login-button']) ?>
            </div>
            <p class="hint-block">
                <?= Yii::t('app', 'If you forgot your password you can {reset_it}.', [
                    'reset_it' => Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']),
                ]) ?>
            </p>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="divider">
            <?= Yii::t('app', 'or') ?>
        </div>

        <div class="oauth-box">
            <div class="registration-prompt">
                <?= Yii::t('app', 'Don\'t have an account? {signUpLink}', [
                    'signUpLink' => Html::a(Yii::t('app', 'Sign up!'), ['/site/signup'])
                ]) ?>
            </div>

            <?= \app\widgets\AuthChoise::widget([
                'options' => ['class' => 'auth-clients-wrapper'],
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => false,
            ]) ?>
        </div>
    </div>
</div>
