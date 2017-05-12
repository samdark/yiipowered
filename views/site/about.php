<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'About YiiPowered';
?>
<div class="site-about">
    <div class="panes-wrapper">
        <div class="pane">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                YiiPowered is a showcase of websites and projects built
                with <a href="http://www.yiiframework.com/">Yii framework</a>.
            </p>

            <h2>API</h2>

            <p>
                YiiPowered provides its data via REST API.
                <?= Html::a('Refer to documentation for its description', ['api1/docs/index']) ?>.
            </p>

            <h2>Project sources</h2>

            <p>
                It is <a href="https://github.com/samdark/yiipowered">open sourced at GitHub</a> under BSD license.
                You've very welcome to contribute in form of issue reports and pull requests.
            </p>
        </div>

        <div class="pane">
            <h2>Team</h2>

            <p>
                Initially the project was created by <a href="https://github.com/samdark">Alexander Makarov</a>.
                Interface designed by <a href="https://www.facebook.com/elena.sandul.14">Olena Sandul</a>.
                You can check <a href="https://github.com/samdark/yiipowered/graphs/contributors">all contributors at GitHub</a>.
            </p>
            <br />
            <br />
            <br />
            <br />
            <p>
                Alexander is currently <a href="https://www.patreon.com/samdark">seeking for patronage</a> to work on
                OpenSource fulltime so if you like this project and Yii in general, consider sponsorship:
            </p>

            <p class="sponsor-link-wrapper">
                <a href="https://www.patreon.com/samdark">Become a sponsor</a>
            </p>
        </div>
    </div>
</div>
