<?php

use yii\helpers\Html;
use homer\sweetalert2\assets\SweetAlert2Asset;
use frontend\assets\SocketIOAsset;
use yii\web\View;
use yii\helpers\Json;
use yii\helpers\Url;

SocketIOAsset::register($this);
$bundle = SweetAlert2Asset::register($this);
$bundle->depends[] = 'homer\assets\HomerAsset';

$this->title = 'Service';
$this->registerCss(<<<CSS
.btn-service {
    font-size: 30px;
}
#wrapper {
    background: #fff;
}
.swal2-footer .btn {
    margin: 5px;
}
CSS
);
$this->registerCss(\Yii::$app->keyStorage->get('background-kiosk', ''));
$this->registerCss(\Yii::$app->keyStorage->get('button-kiosk-position', ''));
$this->registerJsFile(
    YII_DEBUG ? '@web/js/vue/vue.js' : '@web/js/vue/vue.min.js',
    ['position' => View::POS_HEAD]
);
$this->registerCssFile("@web/css/loader.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var device = ' . Json::encode($device) . '; ', View::POS_HEAD);
$currentId = null;
$count = count($sources);
?>
    <div id="app">
        <div class="row">
            <div class="col-xs-7 col-sm-7 col-md-7 col-xs-offset-4 col-sm-offset-4 col-md-offset-4 text-center button-kiosk">
                <?php foreach ($sources as $index => $item): ?>
                    <?php foreach ($item['services'] as $service): ?>
                        <p>
                            <a
                                    class="btn btn-success btn-lg btn-block btn-service"
                                    v-on:click="serviceConfirm(<?= $service['service_id'] ?>, '<?= $service['service_name'] ?>')">
                                <?= Html::encode($service['service_name']) ?>
                            </a>
                        </p>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php
$this->registerJsFile(
    '@web/js/service-kiosknocard.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()],
        'position' => View::POS_END
    ]
);
?>