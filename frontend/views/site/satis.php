<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 19/11/2561
 * Time: 12:01
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use homer\sweetalert2\assets\SweetAlert2Asset;
use yii\web\View;

SweetAlert2Asset::register($this);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);

$this->title = 'ความพึงพอใจ';
?>
<style>
    body {
         font-family: 'Prompt', sans-serif;
    }
    .custom-style {
        position: absolute;
        top: 60%;
        left: 65%;
        transform: translate(-65%, -65%);
        width: 100%;
    }
    .footer-mobile {
        position: fixed;
        bottom: 0;
        width: 100%;
        font-size: 50px;
        color: #fff;
    }
    body{
        background-color: #269abc;
    }
    a, h1, h2, h4{
        color: #fff;
    }
    .btn-lg {
        padding: 5px;
    }
    a.btn-info:hover {
        background-color: #269abc !important;
    }
    body.swal2-height-auto {
        height: inherit !important;
    }
</style>
<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <nav role="navigation">
        <div style="padding-top: 10px;font-size: 25px;">
            &nbsp;<span class="text-primary"><i class="fa fa-hospital-o"></i> <?= Yii::$app->name ?></span>
        </div>
    </nav>
</div>
<div class="row" style="padding-top: 60px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
        <h1>กรุณาโหวตความพึงพอใจของท่าน ที่มีต่อพนักงานที่ให้บริการ</h1>
        <h2>Please press the satisfaction of our services.</h2>
    </div>
</div>
<div class="row custom-style">
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        <a href="<?= Url::to(['create-satis','id' => 1]) ?>" class="btn btn-lg btn-outline btn-info">
            <?= Html::img(Yii::getAlias('@web/imgs/sad.png'), ['class' => 'img-responsive center-block', 'width' => '65%']) ?>
            <h1>
                แย่มาก
            </h1>
            <p></p>
            <h4>
                Very Poor
            </h4>
        </a>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        <a href="<?= Url::to(['create-satis','id' => 2]) ?>" class="btn btn-lg btn-outline btn-info">
            <?= Html::img(Yii::getAlias('@web/imgs/very-confused.png'), ['class' => 'img-responsive center-block', 'width' => '65%']) ?>
            <h1>แย่</h1>
            <p></p>
            <h4>
                Poor
            </h4>
        </a>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        <a href="<?= Url::to(['create-satis','id' => 3]) ?>" class="btn btn-lg btn-outline btn-info">
            <?= Html::img(Yii::getAlias('@web/imgs/confused.png'), ['class' => 'img-responsive center-block', 'width' => '65%']) ?>
            <h1>พอใช้</h1>
            <p></p>
            <h4>
                Fair
            </h4>
        </a>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        <a href="<?= Url::to(['create-satis','id' => 4]) ?>" class="btn btn-lg btn-outline btn-info">
            <?= Html::img(Yii::getAlias('@web/imgs/happy.png'), ['class' => 'img-responsive center-block', 'width' => '65%']) ?>
            <h1>ดี</h1>
            <p></p>
            <h4>
                Good
            </h4>
        </a>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        <a href="<?= Url::to(['create-satis','id' => 5]) ?>" class="btn btn-lg btn-outline btn-info">
            <?= Html::img(Yii::getAlias('@web/imgs/very-happy.png'), ['class' => 'img-responsive center-block', 'width' => '65%']) ?>
            <h1>ดีมาก</h1>
            <p></p>
            <h4>
                Very Good
            </h4>
        </a>
    </div>
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
    </div>
</div>
<div class="footer-mobile">
    <marquee id="marquee" direction="left"><i class="fa fa-hospital-o"></i> เทศบาลนครอุดรธานียินดีให้บริการ </marquee>
</div>

<?php
$this->registerJs(<<<JS
$('a').on('click', function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        method: "GET",
        url: baseUrl + url,
        dataType: "json",
        success: function (response) {
            swal({
                 type: 'success',
                 title: 'ขอบคุณที่มาใช้บริการ',
                 showConfirmButton: false,
                 timer: 3000,
                 footer: 'เทศบาลนครอุดรธานี'
            });
            $('.btn-info:hover').css('background-color','#269abc');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            swal({
                type: 'error',
                title: textStatus,
                text: errorThrown,
            });
        }
    });
});
JS
);
?>