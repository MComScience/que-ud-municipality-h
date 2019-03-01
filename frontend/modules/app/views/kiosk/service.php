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
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <div>
                <?= Html::img(Yii::getAlias('@web/imgs/customer-service.png'),['class' => 'img-responsive center-block', 'style' => 'max-width: 45px;']) ?>
            </div>
            <p><h3>เทศบาลอุดรธานี ยินดีให้บริการ</h3></p>
        </div>
    </div>
    <div class="row">
        <?php foreach($sources as $index => $item): ?>
            <div class="col-xs-5 col-sm-5 col-md-5 text-center">
                <p>
                    <h3>
                        <?= Html::img(Yii::getAlias('@web/imgs/check-mark.png'),['class' => 'img-responsive', 'style' => 'display: inline-block;max-width: 45px;']) ?> 
                        <?= Html::encode($item['service_group_name']) ?>
                    </h3>
                </p>
                <?php foreach($item['services'] as $service): ?>
                    <p>
                        <a href="#" 
                        class="btn btn-success btn-lg btn-block btn-service" 
                        v-on:click="serviceConfirm(<?= $service['service_id'] ?>, '<?= $service['service_name'] ?>')">
                            <?= Html::encode($service['service_name']) ?>
                        </a>
                    </p>
                <?php endforeach; ?>
            </div>
            <?php if($currentId !== $index && $count !== ($index + 1)): ?>
                <?php $currentId = $index; ?>
                <div class="col-xs-2 col-sm-2 col-md-2 text-center">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php
$this->registerJsFile(
    '@web/js/service-kiosk.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()],
        'position' => View::POS_END
    ]
);
?>