<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 */

$this->title = Yii::t('project', 'Add project');
?>

<div class="project-add">

    <div class="form-box">
        <div class="form-wrapper">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
