<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Возникла ошибка :(
    </p>
    <p>
        Скорее всего мы о ней уже знаем, но, если что, пишите <a href="mailto:sam@rmcreative.ru">@samdark</a>.
    </p>

</div>
