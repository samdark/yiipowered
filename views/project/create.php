<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Project $model
 */
?>

<div class="project-add">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
