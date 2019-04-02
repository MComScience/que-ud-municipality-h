<?php
use yii\helpers\Html;
use homer\assets\HomerAsset;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use homer\widgets\Modal;
use homer\sweetalert2\assets\SweetAlert2Asset;
use yii\widgets\Pjax;
use frontend\assets\SocketIOAsset;

SocketIOAsset::register($this);
SweetAlert2Asset::register($this);
HomerAsset::register($this);
$this->registerJs('var modelQue = '. Json::encode($modelQue).';',View::POS_HEAD);
$this->registerCss(<<<CSS
.navy-bg, .bg-primary {
    background-color: #1ab394;
    color: #ffffff;
}
.p-lg {
    padding-top: 10px !important;
    padding: 30px;
}
.widget-head-color-box {
    margin-top: 10px;
}
img.circle-border {
    border: 6px solid #FFFFFF;
    border-radius: 50%;
}
.m-b-md {
    margin-bottom: 20px;
}
.modal-header {padding: 5px;}
.btn {
    width: 165px;
}
CSS
);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->title = 'Mobile View';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="bg-gray">
<?php $this->beginBody() ?>
<?php Pjax::begin(['id' => 'pjax-mobile-view']); ?>
<div class="container" style="width: auto;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="widget-head-color-box navy-bg p-lg text-center" style="border-radius: 5px 5px 5px 5px;">
                <div class="mobile-content">
                    <img src="<?= Yii::getAlias('@web/imgs/udh-logo.png') ?>" class="rounded-circle img-responsive center-block" alt="profile" width="100px" height="100px">
                    <div class="m-b-md">
                        <h2 class="font-bold no-margins">
                            เทศบาลนครอุดรธานี
                        </h2>
                        <small></small>
                    </div>
                    <img src="<?= Yii::getAlias('@web/imgs/default-avatar.png') ?>" class="rounded-circle circle-border m-b-md img-responsive center-block" alt="profile" width="100px" height="100px">
                    <div class="m-b-md">
                        <h3 class="font-bold no-margins">
                            <?= $modelQue['pt_name'] ?>
                        </h3>
                        <h1 class="font-bold no-margins" style="font-size: 40px;">
                            <?= $modelQue['que_num'] ?>
                        </h1>
                    </div>
                    <p>
                        <button type="button" class="btn btn-lg btn-primary" style="border-color: #fff;">สถานะ / จุดบริการ</button>
                    </p>
                    <div class="m-b-md">
                        <h2 class="font-bold no-margins">
                            <?= $modelQue->queStatus->que_status_name; ?>
                        </h2>
                    </div>
                    <p>
                        <button type="button" class="btn btn-lg btn-primary" style="border-color: #fff;">รออีก</button>
                    </p>
                    <div class="m-b-md">
                        <h2 class="font-bold no-margins" id="count">
                            <?= $count ?> คิว
                        </h2>
                    </div>
                    <p>
                        <button type="button" class="btn btn-lg btn-primary" style="border-color: #fff;">คิวล่าสุด</button>
                    </p>
                    <div class="m-b-md">
                        <h2 class="font-bold no-margins">
                            <span id="last-call">-</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
<?php
Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    'options' => ['class' => 'modal modal-danger','tabindex' => false,],
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

Modal::end();

$this->registerJs(<<<JS
$(function () {
    socket.on('on-show-display', (res) => {
        if(res.artist.modelQue.service_id == modelQue.service_id && res.artist.modelQue.service_group_id == modelQue.service_group_id){
            $('#last-call').html(res.artist.modelQue.que_num);
        }
        if(res.artist.modelQue.que_ids == modelQue.que_ids){
            swal({
                type: 'warning',
                title: 'ถึงคิวแล้วครับ!',
                text: "เชิญที่ " + res.artist.modelCounterService.counter_service_name,
            });
            $.pjax.reload({container: '#pjax-mobile-view'});
        }else{
            $.ajax({
                method: "GET",
                url: baseUrl + "/qrcode/get-count",
                dataType: "json",
                data: {id: modelQue.que_ids},
                success: function(count){
                    $('#count').html(count + ' คิว');
                },
                error: function(msg){
                    console.error(msg);
                }
            });
        }
    });
});
JS
);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>