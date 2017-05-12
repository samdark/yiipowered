<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">
    <div class="center-box">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
            An error occured :(
        </p>
        <p>
            Most probably we're aware but it won't hurt emailing <a href="mailto:sam@rmcreative.ru">@samdark</a>.
        </p>
    </div>
</div>
