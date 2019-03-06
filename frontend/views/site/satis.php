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
$this->registerCss(\Yii::$app->keyStorage->get('satis-style',''));

$this->title = 'ความพึงพอใจ';
?>
<style>
    body {
         font-family: 'Prompt', sans-serif;
    }
    .custom-style {
        position: absolute;
        top: 65%;
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
        background-color: #43A047;
    }
    a, h1, h2, h4{
        color: #fff;
    }
    .btn-lg {
        padding: 5px;
        border-radius: 50px;
        margin-bottom: 15px;
    }
    a.btn-info:hover {
        background-color: #269abc !important;
    }
    body.swal2-height-auto {
        height: inherit !important;
    }
    img.img-satis {
        /* margin-top: 4px; */
        border: 2px solid #fff !important;
        border-radius: 50%;
    }
    /* VERTICAL */
    @keyframes vertical {
        0%{transform:translate(0,-3px)}
        4%{transform:translate(0,3px)}
        8%{transform:translate(0,-3px)}
        12%{transform:translate(0,3px)}
        16%{transform:translate(0,-3px)}
        20%{transform:translate(0,3px)}
        22%,100%{transform:translate(0,0)}
    }
    .faa-vertical.animated,
    .faa-vertical.animated-hover:hover,
    .faa-parent.animated-hover:hover > .faa-vertical {
        animation: vertical 2s ease infinite;
    }
    .faa-vertical.animated.faa-fast,
    .faa-vertical.animated-hover.faa-fast:hover,
    .faa-parent.animated-hover:hover > .faa-vertical.faa-fast {
        animation: vertical 1s ease infinite;
    }
    .faa-vertical.animated.faa-slow,
    .faa-vertical.animated-hover.faa-slow:hover,
    .faa-parent.animated-hover:hover > .faa-vertical.faa-slow {
        animation: vertical 4s ease infinite;
    }

    /* HORIZONTAL */
    @keyframes horizontal {
        0%{transform:translate(0,0)}
        6%{transform:translate(5px,0)}
        12%{transform:translate(0,0)}
        18%{transform:translate(5px,0)}
        24%{transform:translate(0,0)}
        30%{transform:translate(5px,0)}
        36%,100%{transform:translate(0,0)}
    }
    .faa-horizontal.animated,
    .faa-horizontal.animated-hover:hover,
    .faa-parent.animated-hover:hover > .faa-horizontal {
        animation: horizontal 2s ease infinite;
    }
    .faa-horizontal.animated.faa-fast,
    .faa-horizontal.animated-hover.faa-fast:hover,
    .faa-parent.animated-hover:hover > .faa-horizontal.faa-fast {
        animation: horizontal 1s ease infinite;
    }
    .faa-horizontal.animated.faa-slow,
    .faa-horizontal.animated-hover.faa-slow:hover,
    .faa-parent.animated-hover:hover > .faa-horizontal.faa-slow {
        animation: horizontal 3s ease infinite;
    }
    
    .custom-style .btn {
        border: 2px solid #fff;
        box-shadow: 0 5px #f7f9fa;
        display: block;
        max-width: 100%;
        height: auto;
    }
</style>
<!-- Header -->
<?php /*
<div id="header">
    <div class="color-line">
    </div>
    <nav role="navigation">
        <div style="padding-top: 10px;font-size: 25px;">
            &nbsp;<span class="text-primary"><i class="fa fa-hospital-o"></i> <?= Yii::$app->name ?></span>
        </div>
    </nav>
</div>
*/?>
<!-- <div class="row" style="padding-top: 60px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
        <h1>กรุณาโหวตความพึงพอใจของท่าน ที่มีต่อพนักงานที่ให้บริการ</h1>
        <h2>Please press the satisfaction of our services.</h2>
    </div>
</div> -->
<div class="custom-style container">
    <div class="row" style="margin: 5px;">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
            <a href="<?= Url::to(['create-satis','id' => 5]) ?>" class="btn btn-lg btn-success">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                        <?= Html::img(Yii::getAlias('@web/imgs/very-happy.png'), ['class' => 'img-responsive center-block img-satis faa-vertical animated']) ?>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <h1>ดีมาก</h1>
                        <h4>
                            Very Good
                        </h4>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row" style="margin: 5px;">
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
            <a href="<?= Url::to(['create-satis','id' => 4]) ?>" class="btn btn-lg btn-success">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                        <?= Html::img(Yii::getAlias('@web/imgs/happy.png'), ['class' => 'img-responsive center-block img-satis faa-vertical animated']) ?>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <h1>ดี</h1>
                        <h4>
                            Good
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
            <a href="<?= Url::to(['create-satis','id' => 3]) ?>" class="btn btn-lg btn-warning">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                        <?= Html::img(Yii::getAlias('@web/imgs/confused.png'), ['class' => 'img-responsive center-block img-satis faa-vertical animated']) ?>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <h1>พอใช้</h1>
                        <h4>
                            Fair
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        </div>
    </div>
    <div class="row" style="margin: 5px;">
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
            <a href="<?= Url::to(['create-satis','id' => 2]) ?>" class="btn btn-lg h-bg-orange">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                        <?= Html::img(Yii::getAlias('@web/imgs/very-confused.png'), ['class' => 'img-responsive center-block img-satis faa-vertical animated']) ?>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <h1>แย่</h1>
                        <h4>
                            Poor
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
            <a href="<?= Url::to(['create-satis','id' => 1]) ?>" class="btn btn-lg btn-danger">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                        <?= Html::img(Yii::getAlias('@web/imgs/sad.png'), ['class' => 'img-responsive center-block img-satis faa-vertical animated']) ?>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <h1>
                            แย่มาก
                        </h1>
                        <h4>
                            Very Poor
                        </h4>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


<!-- <div class="footer-mobile">
    <marquee id="marquee" direction="left"><i class="fa fa-hospital-o"></i> เทศบาลนครอุดรธานียินดีให้บริการ </marquee>
</div> -->

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