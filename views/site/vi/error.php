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
            Có lỗi xảy ra :(
        </p>
        <p>
            Vui lòng gửi email tới <a href="mailto:sam@rmcreative.ru">@samdark</a> để báo cáo các lỗi xảy ra.
        </p>
    </div>
</div>
