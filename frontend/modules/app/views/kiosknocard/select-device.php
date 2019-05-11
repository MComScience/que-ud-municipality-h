<?php
use yii\helpers\Html;
use frontend\assets\SocketIOAsset;
use homer\sweetalert2\assets\SweetAlert2Asset;

$bundle = SweetAlert2Asset::register($this);
$bundle->depends[] = 'homer\assets\HomerAsset';
SocketIOAsset::register($this);

$this->title = 'Select Device';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <?php foreach($devices as $device): ?>
        <div class="col-md-3">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="text-center">
                        <h4 class="m-b-xs"><?= $device['device_name'] ?></h4>
                        <p class="font-bold text-success"></p>
                        <div class="m">
                            <i class="pe-7s-monitor fa-5x"></i>
                        </div>
                        <?= Html::a('Open',['/app/kiosk/service', 'deviceId' => $device['device_id']],['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$this->registerJs(<<<JS
socket.on('DEVICE_CONNECTED', function (res) {
    $.ajax({
        url: '/app/kiosk/create-device',
        type: 'POST',
        data: {device_name: res.comName},
        dataType: 'json',
        error: function (jqXHR, textStatus, errorThrown) {
            swal({
                type: 'error',
                title: textStatus,
                text: errorThrown,
            });
        },
        success: function(response) {
            if(response !== 'device already'){
                window.location.reload();
            }
        },
    });
});
JS
);
?>