<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('user', 'Update {modelClass}: ', [
    'modelClass' => 'пользователя',
]) . ' ' . $model->username;
?>
<div class="user-update">

    <div class="form-wrap">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
