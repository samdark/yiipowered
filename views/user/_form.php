<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
use app\widgets\Avatar;
use app\components\UserPermissions;

/* @var $this yii\web\View */
/* @var $model app\models\User */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'username')->textInput() ?>
                <?= $form->field($model, 'fullname')->textInput() ?>
                <?= $form->field($model, 'avatarFile')->fileInput() ?>

                <div class="form-group current-avatar">
                    <strong><?= Yii::t('user', 'Current avatar') ?></strong> <br />
                    <?php if($model->avatar) { ?>
                        <?= Html::img($model->getAvatarImage(), ['alt' => $model->username, 'class' => 'img-thumbnail']) ?>
                    <?php } else { ?>
                        <?= Avatar::widget([
                            'user' => $model,
                            'size' => 165,
                        ]) ?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-6">
                <?php if(Yii::$app->user->can(UserPermissions::MANAGE_USERS)) { ?>
                    <?= $form->field($model, 'status')->dropDownList(User::getStatuses()) ?>
                <?php } ?>
            </div>
        </div>



        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
