<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'О проекте YiiPowered';
?>
<div class="site-about">
    <div class="panes-wrapper">
        <div class="pane">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                YiiPowered призван показать, какие сайты и проекты были сделаны
                с использованием <a href="http://www.yiiframework.com/">фреймворка Yii</a>.
            </p>

            <h2>API</h2>

            <p>
                Данные YiiPowered доступны через REST API.
                <?= Html::a('Описание смотрите на странице документации', ['api1/docs/index']) ?>.
            </p>

            <h2>Исходный код</h2>

            <p>
                Код проекта <a href="https://github.com/samdark/yiipowered">доступен на GitHub</a>
                под лицензией BSD. Сообщения об ошибках и pull request-ы привествуются.
            </p>

        </div>
        <div class="pane">
            <h2>Команда проекта</h2>

            <p>
                Изначально проект создан <a href="https://github.com/samdark">Александром Макаровым</a>.
                Дизайн проекта разработан <a href="https://www.facebook.com/elena.sandul.14">Еленой Сандул</a>.
                Полный список участвовавших <a href="https://github.com/samdark/yiipowered/graphs/contributors">
                    можно найти на GitHub</a>.
            </p>

            <p>
                Александр в данный момент ищет
                <a href="https://www.patreon.com/samdark">спонсоров для постоянной работы над OpenSource</a>.
                Если вам нравится этот проект и Yii в общем &mdash; присоединяйтесь:
            </p>

            <p class="sponsor-link-wrapper">
                <a href="https://www.patreon.com/samdark">Стать спонсором</a>
            </p>
        </div>
    </div>
</div>
