<?php
use homer\widgets\Table;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use frontend\assets\ModernBlinkAsset;
use frontend\assets\SocketIOAsset;
use homer\assets\FontAwesomeAsset;
use homer\sweetalert2\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;

SweetAlert2Asset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);
FontAwesomeAsset::register($this);
ModernBlinkAsset::register($this);

$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
?>
<style>
    @media (min-width: 768px) {
        table thead tr th {
            /* text-align: center; */
            font-size: 30px;
        }
        table tbody tr td {
            /* text-align: center; */
            font-size: 40px;
        }
        #marquee {
            font-size: 40px;
        }
    }

    /* Medium devices (desktops, 992px and up) */

    @media (min-width: 992px) {
        table thead tr th {
            /* text-align: center; */
            font-size: 50px;
        }
        table tbody tr td {
            /* text-align: center; */
            font-size: 60px;
        }
        #marquee {
            font-size: 50px;
        }
    }

    /* Large devices (large desktops, 1200px and up) */

    @media (min-width: 1920px) {
        table thead tr th {
            /* text-align: center; */
            font-size: 70px;
        }
        table tbody tr td {
            /* text-align: center; */
            font-size: 80px;
        }
        #marquee {
            font-size: 80px;
        }
    }

    .container {
        width: auto;
    }

    table {
        border-spacing: 5px;
        border-collapse: unset;
        border-spacing: 0 10px;
    }

    .table>thead>tr>th {
        vertical-align: bottom;
        border-bottom: 0px;
    }

    table#tb-hold tbody tr.odd td {
        border-top: 0px;
    }

    table#tb-hold {
        border-spacing: 5px;
        border-collapse: unset;
        border-spacing: 0 0px;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }

    table {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }

    .table>tbody>tr>td{
        padding: 0px;
    }

    /* ribbon */

    .ribbon.ribbon-round.ribbon-right {
        border-top-right-radius: 5px !important;
        border-bottom-right-radius: 5px !important;
        border-top-left-radius: 5px !important;
        border-bottom-left-radius: 5px !important;
    }

    .ribbon.ribbon-shadow.ribbon-right,
    .mt-element-ribbon .ribbon.ribbon-shadow.ribbon-vertical-right {
        box-shadow: -2px 2px 7px rgba(0, 0, 0, 0.4);
    }

    .ribbon.ribbon-color-danger {
        background-color: #ed6b75;
        color: #fff;
    }

    .ribbon.ribbon-color-danger:after {
        border-color: #e73d4a;
    }

    .ribbon.ribbon-color-danger>.ribbon-sub {
        background-color: #ed6b75;
        color: #4f0a0f;
    }

    .ribbon.ribbon-color-danger>.ribbon-sub:after {
        border-color: #a91520;
        border-left-color: #ed6b75;
        border-right-color: #ed6b75;
    }

    .ribbon.ribbon-right {
        float: right;
        clear: right;
        margin: 10px -2px 0 0;
    }

    .ribbon {
        padding: 0.5em 1em;
        z-index: 5;
        float: left;
        margin: 10px 0 0 -2px;
        clear: left;
        position: relative;
        background-color: #bac3d0;
        color: #384353;
    }

    .uppercase {
        text-transform: uppercase !important;
    }

    .ribbon.ribbon-color-danger:after {
        border-color: #e73d4a;
    }

    .ribbon.ribbon-border-dash:after {
        border: 1px solid;
        border-style: dashed;
        content: '';
        position: absolute;
        top: 5px;
        bottom: 5px;
        left: 5px;
        right: 5px;
    }

    .ribbon:after {
        border-color: #62748f;
    }

    .ribbon>.ribbon-sub.ribbon-clip.ribbon-right:after {
        border-left: 21px solid;
        border-right: 20px solid;
        border-bottom: 1em solid transparent !important;
        bottom: -1em;
        content: '';
        height: 0;
        left: 0;
        position: absolute;
        width: 0;
    }

    .ribbon.ribbon-color-default {
        background-color: #bac3d0;
    }

    .ribbon.ribbon-color-primary {
        background-color: #337ab7;
        color: #fff;
    }

    .ribbon>.ribbon-sub {
        z-index: -1;
        position: absolute;
        padding: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    .ribbon.ribbon-border-dash-vert:after {
        border-top: none;
        border-bottom: none;
        border-left: 1px solid;
        border-right: 1px solid;
        border-left-style: dashed;
        border-right-style: dashed;
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 5px;
        right: 5px;
    }

    .ribbon.ribbon-color-primary>.ribbon-sub:after {
        border-color: #122b40 #337ab7 !important;
    }

    .ribbon>.ribbon-sub.ribbon-bookmark:after {
        border-left: 21px solid;
        border-right: 20px solid;
        border-bottom: 1em solid transparent !important;
        bottom: -1em;
        content: '';
        height: 0;
        left: 0;
        position: absolute;
        width: 0;
    }

    .ribbon>.ribbon-sub:after {
        border-color: #62748f #bac3d0;
    }
    .color-white {
        color: #fff;
    }
    .width50 {
        width: 50%;
        vertical-align: middle !important;
    }
    .table-bordered {
        border: 0px;
    }
    table.dataTable{
        margin-top: 0px !important;
        border-spacing: 0 0px;
    }

</style>
<div class="row" style="background-color: #62cb31;">
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <?= Html::img(Yii::getAlias('@web/imgs/udoncity-logo.png'),['class' => 'img-responsive image-logo']); ?>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <h1 class="color-white header-label-th">เทศบาลอุดรธานี</h1>
        <h2 class="color-white header-label-en">UDON THANI MUNICPALITY</h2>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
        <h1 class="color-white header-label-date">
            <?= Yii::$app->formatter->asDate('now', 'php:lที่ m F').' '.(Yii::$app->formatter->asDate('now', 'php:Y') + 543); ?>
        </h1>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-center clock-display" style="font-size: 45px;color: #fff">
        <span class="color-white header-label-clock">เวลา</span>
        <span class="time__hours"><?= Yii::$app->formatter->asDate('now','php:H') ?></span>:<span class="time__min"><?= Yii::$app->formatter->asDate('now','php:i') ?></span>:<span class="time__sec"><?= Yii::$app->formatter->asDate('now','php:s') ?></span>
    </div>
</div>
<div class="row">
<?php foreach($options as $option): ?>
<?php
$this->registerCss($option['config']['display_css']);
?>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
            <?php
            echo Table::widget([
                'tableOptions' => ['class' => 'table table-bordered','id' => 'tb-display'.$option['config']['display_ids']],
                'beforeHeader' => [
                    [
                        'columns' => [
                            ['content' => $option['config']['text_th_left'], 'options' => ['style' => 'text-align: center;width: 100%;','class' => 'th-left', 'colspan' => 2]],
                            //['content' => $option['config']['text_th_right'], 'options' => ['style' => 'text-align: center;width: 20%;','class' => 'th-right']],
                            //['content' => '', 'options' => []],
                        ],
                    ],
                ],
                'datatableOptions' => [
                    "clientOptions" => [
                        "ajax" => [
                            "url" => Url::base(true)."/app/display/data-display",
                            "type" => "POST",
                            "data" => ['config' => $option['config']],
                        ],
                        "dom" => "t",
                        "responsive" => true,
                        "autoWidth" => false,
                        "deferRender" => true,
                        "ordering" => false,
                        "pageLength" => empty($option['config']['page_length']) ? -1 : $option['config']['page_length'],
                        "columns" => [
                            ["data" => "counter_number","defaultContent" => "", "className" => "text-center td-left width50","orderable" => false],
                            ["data" => "que_number","defaultContent" => "", "className" => "text-center td-right width50","orderable" => false],
                            //["data" => "data","orderable" => false, "visible" => false],
                        ],
                        "language" => [
                            "loadingRecords" => "Loading...",
                            "emptyTable" => "ไม่มีข้อมูลคิว"
                        ]
                    ],
                    'clientEvents' => [
                        'error.dt' => 'function ( e, settings, techNote, message ){
                        e.preventDefault();
                        console.warn("error message",message);
                    }'
                    ],
                ],
            ]);
            ?>
    </div>
<?php
$display_ids = $option['config']['display_ids'];
$displayId = '#tb-display'.$display_ids;
$config = 'config'.$display_ids;
$services = 'services'.$display_ids;
$counters = 'counters'.$display_ids;
$this->registerJs('var '.$config.' = '. Json::encode($option['config']).';',View::POS_HEAD);
$this->registerJs('var '.$services.' = '. Json::encode($option['services']).';',View::POS_HEAD);
$this->registerJs('var '.$counters.' = '. Json::encode($option['counters']).';',View::POS_HEAD);
$this->registerJs(<<<JS
//Socket Events
$(function () {
    socket.on('on-show-display', (res) => { //เรียกคิว
        console.log(res)
        if (jQuery.inArray(res.artist.modelCaller.counter_service_id.toString(), {$counters}) !== -1 &&
            jQuery.inArray(res.artist.modelQue.service_id.toString(), {$services}) !== -1) {
            Que.reloadDisplay('{$displayId}');
            setTimeout(function () {
                Que.blink(res, {$config}); //สั่งกระพริบ
            }, 1000);
        }
    }).on('on-hold', (res) => {
        if (jQuery.inArray(res.modelCaller.counter_service_id.toString(), {$counters}) !== -1 &&
            jQuery.inArray(res.modelQue.service_id.toString(), {$services}) !== -1) {

            Que.removeRow(res, '{$displayId}');
            $('.' + res.modelQue.que_num).html('-');
            Que.addRows('{$displayId}', {$config});
        }
    }).on('update-display', (res) => {
        if (res.model.display_ids == {$config}.display_ids) {
            location.reload();
        }
    }).on('on-end', (res) => {
        if (jQuery.inArray(res.modelCaller.counter_service_id.toString(), {$counters}) !== -1 &&
            jQuery.inArray(res.modelQue.service_id.toString(), {$services}) !== -1) {
            Que.removeRow(res, '{$displayId}');
            $('.' + res.modelQue.que_num).html('-');
            Que.addRows('{$displayId}', {$config});
        }
    });
});
JS
);
?>
<?php endforeach; ?>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <marquee id="marquee" direction="left"><i class="fa fa-hospital-o"></i> <?= \Yii::$app->keyStorage->get('marquee-label', '') ?></marquee>
    </div>
</div>
<?php
$this->registerJs(<<<JS
var Que = {
    reloadDisplay: function (displayId) {
        var table = $(displayId).DataTable();
        table.ajax.reload();
    },
    removeRow: function (res, displayId) {
        var table = $(displayId).DataTable();
        table.row('#' + res.modelQue.que_num).remove().draw();
    },
    addRows: function(displayId, config){
        var table = $(displayId).DataTable();
        if (table.rows().data().length < config.page_length) {
            for (i = table.rows().data().length; i < config.page_length; i++) {
                table.row.add( {
                    "counter_number": "<span class=\"\">-</span>",
                    "counter_service_call_number": "-",
                    "data": [],
                    "que_number": "-",
                } ).draw();
            }
        }
    },
    blink: function (res, config) { //สั่งกระพริบ
        console.log('res', res);
        console.log('config', config)
        if (config.que_column_length > 1) {
            $('span.' + res.title + ', .' + res.artist.modelCounterService.counter_service_call_number).modernBlink({
                duration: 1000,
                iterationCount: 7,
                auto: true
            });
        } else {
            $('.' + res.title).modernBlink({
                duration: 1000,
                iterationCount: 7,
                auto: true
            });
        }
    },
};
$(document).ready(function(){
    if($(".clock-display")[0]){
        var a = new Date();
        a.setDate(a.getDate()),
        setInterval(function(){
            var a=(new Date()).getSeconds();
            $(".time__sec").html((a<10?"0":"")+a);
        },1e3);
        setInterval(function(){
            var a=(new Date()).getMinutes();
            $(".time__min").html((a<10?"0":"")+a);
        },1e3);
        setInterval(function(){
            var a=(new Date()).getHours();
            $(".time__hours").html((a<10?"0":"")+a);
        },1e3);
    }
});
JS
);
?>