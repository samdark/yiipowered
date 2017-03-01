<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-sm-3 col-sm-offset-1">
            <?= yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => false,
            ]) ?>
        </div>
        <div class="col-sm-2">
            <h2><?= Yii::t('app', 'OR') ?></h2>
        </div>
        <div class="col-sm-5">
            <h2><?= Yii::t('app', 'fill out the following form') ?></h2>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <p class="hint-block">
                    <?= Yii::t('app', 'If you forgot your password you can {reset_it}.', [
                        'reset_it' => Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']),
                    ]) ?>
                </p>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>