<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Về YiiPowered';
?>
<div class="site-about">
    <div class="panes-wrapper">
        <div class="pane">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                YiiPowered là website giới thiệu các dự án được xây dựng với
                 <a href="http://www.yiiframework.com/">Yii framework</a>.
            </p>

            <h2>API</h2>

            <p>
                YiiPowered có cung cấp dữ liệu qua REST API.
                <?= Html::a('Tham khảo tài liệu để biết thêm thông tin', ['api1/docs/index']) ?>.
            </p>

            <h2>Mã nguồn dự án</h2>

            <p>
                Dự án có cung cấp <a href="https://github.com/samdark/yiipowered">mã nguồn mở tại GitHub</a> cùng với giấy phép BSD.
                Bạn rất được chào mừng để đóng góp các báo cáo lỗi và tạo mới các yêu cầu thay đổi.
            </p>
        </div>

        <div class="pane">
            <h2>Đội dự án</h2>

            <p>
                Dự án được khởi tạo và phát triển bởi <a href="https://github.com/samdark">Alexander Makarov</a>.
                Giao diện được thiết kế bởi <a href="https://www.facebook.com/elena.sandul.14">Olena Sandul</a>.
                Bạn có thể kiểm tra mục <a href="https://github.com/samdark/yiipowered/graphs/contributors"> đóng góp tại GitHub</a> để xem thêm.
            </p>

            <p>
                Alexander hiện tại đang <a href="https://www.patreon.com/samdark">tìm kiếm sự bảo trợ</a> để làm việc các dự án
                toàn thời gian cho các dự án mã nguồn mở, vì thế nếu bạn ưa thích dự án này và mã nguồn Yii, bạn có thể xem xét để bảo trợ:
            </p>

            <p class="sponsor-link-wrapper">
                <a href="https://www.patreon.com/samdark">Tài trợ phát triển</a>
            </p>
        </div>
    </div>
</div>
