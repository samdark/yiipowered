<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'About YiiPowered';
?>
<div class="site-about row">
    <div class="col-xs-12 page">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>YiiPowered is a showcase of websites and projects built with <a href="http://www.yiiframework.com/">Yii framework</a>.</p>

        <h2>API</h2>

        <p>YiiPowered provides its data via REST API. <?= Html::a('Refer to documentation for its description', ['api1/docs/index']) ?>.</p>

        <h2>Project sources</h2>

        <p>It is <a href="https://github.com/samdark/yiipowered">open sourced at GitHub</a> under BSD license.
           You've very welcome to contribute in form of issue reports and pull requests.</p>

        <h2>Team</h2>

        <p>Initially the project was created by <a href="https://github.com/samdark">Alexander Makarov</a>.
            You can check <a href="https://github.com/samdark/yiipowered/graphs/contributors">all contributors at GitHub</a>.</p>

        <p>Alexander is currently <a href="https://www.patreon.com/samdark">seeking for patronage</a> to work on
           OpenSource fulltime so if you like this project and Yii in general,
           <a href="https://www.patreon.com/samdark">consider sponsorship</a>.
        </p>
    </div>
</div>
