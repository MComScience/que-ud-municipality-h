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
$this->registerCss(\Yii::$app->keyStorage->get('satis-style', ''));

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

        body {
            background-color: #43A047;
        }

        a, h1, h2, h4 {
            color: #fff;
        }

        .custom-style a.btn-lg {
            padding: 30px;
            border-radius: 100px;
            margin-top: 30px;
            margin-bottom: 15px;
            opacity: 0;
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
            0% {
                transform: translate(0, -3px)
            }
            4% {
                transform: translate(0, 3px)
            }
            8% {
                transform: translate(0, -3px)
            }
            12% {
                transform: translate(0, 3px)
            }
            16% {
                transform: translate(0, -3px)
            }
            20% {
                transform: translate(0, 3px)
            }
            22%, 100% {
                transform: translate(0, 0)
            }
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
            0% {
                transform: translate(0, 0)
            }
            6% {
                transform: translate(5px, 0)
            }
            12% {
                transform: translate(0, 0)
            }
            18% {
                transform: translate(5px, 0)
            }
            24% {
                transform: translate(0, 0)
            }
            30% {
                transform: translate(5px, 0)
            }
            36%, 100% {
                transform: translate(0, 0)
            }
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
            display: block;
            height: auto;

        }

        .swal2-radio {
            display: table !important;
        }

        .swal2-popup .swal2-checkbox label, .swal2-popup .swal2-radio label {
            display: block !important;
            font-size: 2em;
        }

        input[type=radio] {
            width: 30px;
            height: 30px;
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
*/ ?>
    <!-- <div class="row" style="padding-top: 60px;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
            <h1>กรุณาโหวตความพึงพอใจของท่าน ที่มีต่อพนักงานที่ให้บริการ</h1>
            <h2>Please press the satisfaction of our services.</h2>
        </div>
    </div> -->
    <div class="custom-style container">
        <div class="row">
            <!--มากที่สุด-->
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center" style="margin-left: 11% !important;">
                <a href="<?= Url::to(['create-satis', 'id' => 5]) ?>" class="btn btn-lg btn-success" data-id="5"
                   data-label="มากที่สุด">
                    <div class="row">

                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <h1>มากที่สุด</h1>
                            <h4>

                            </h4>
                        </div>
                    </div>
                </a>
            </div>
            <!--มากที่สุด-->
            <!--มาก-->
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                <a href="<?= Url::to(['create-satis', 'id' => 4]) ?>" class="btn btn-lg btn-success" data-id="4"
                   data-label="มาก">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <h1>มาก</h1>
                            <h4>

                            </h4>
                        </div>
                    </div>
                </a>
            </div>
            <!--มาก-->
        </div>
        <div class="row" style="margin: 5px;">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                <a href="<?= Url::to(['create-satis', 'id' => 3]) ?>" class="btn btn-lg btn-warning" data-id="3"
                   data-label="ปานกลาง">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <h1>ปานกลาง</h1>
                            <h4>

                            </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                <a href="<?= Url::to(['create-satis', 'id' => 2]) ?>" class="btn btn-lg h-bg-orange" data-id="2"
                   data-label="น้อย">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <h1>น้อย</h1>
                            <h4>

                            </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                <a href="<?= Url::to(['create-satis', 'id' => 1]) ?>" class="btn btn-lg btn-danger" data-id="1"
                   data-label="น้อยมาก">
                    <div class="row">

                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <h1>
                                น้อยมาก
                            </h1>
                            <h4>

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
    var id = $(this).data('id');
    var label = $(this).data('label');
    if(id == 2 || id == 1) {
        Swal.fire({
            title: 'กรุณาเลือกเหตุผลของคุณ',
            text: label,
            type: 'warning',
            showCancelButton: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            footer: `
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
                    <p><button class="btn btn-lg btn-block btn-info on-click-event" data-id="1" data-label="พูดจาไม่สุภาพ"><i class="fa fa-hand-pointer-o"></i> พูดจาไม่สุภาพ</button></p>
                    <p><button class="btn btn-lg btn-block btn-info on-click-event" data-id="2" data-label="ไม่ใส่ใจบริการ"><i class="fa fa-hand-pointer-o"></i> ไม่ใส่ใจบริการ</button></p>
                    <p><button class="btn btn-lg btn-block btn-info on-click-event" data-id="3" data-label="ทำงานไม่ตรงเวลา"><i class="fa fa-hand-pointer-o"></i> ทำงานไม่ตรงเวลา</button></p>
                    <p><button class="btn btn-lg btn-block btn-info on-click-event" data-id="4" data-label="ล่าช้า"><i class="fa fa-hand-pointer-o"></i> ล่าช้า</button></p>
                    <p><button class="btn btn-lg btn-block btn-danger on-cancel">ยกเลิก</button></p>
                </div>
            </div>
            `,
            onBeforeOpen: () => {
                $('.swal2-footer').css('display', 'table');
                $('button.on-cancel').on('click', function() {
                    Swal.close();
                });
                $('button.on-click-event').on('click', function(e) {
                    onConfirm(label, $(this).data('id'), url, $(this).data('label'));
                });
            },
        }).then((result) => {
            if(result.value){
                
            }
        });
    } else {
        Swal.fire({
            title: 'ยืนยันความพึงพอใจ?',
            text: 'ความพึงพอใจของคุณคือ ' + label,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ตกลง',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        method: "GET",
                        url: baseUrl + url,
                        dataType: "json",
                        data: {
                            reason: 'false'
                        },
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
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                swal({
                    type: 'success',
                    title: 'ขอบคุณที่มาใช้บริการ',
                    showConfirmButton: false,
                    timer: 3000,
                    footer: 'เทศบาลนครอุดรธานี'
                });
                $('.btn-info:hover').css('background-color','#269abc');
            }
        });
    }
});

onConfirm = function (label, reason, url, reasonLabel) {
    Swal.fire({
        title: 'ยืนยันความพึงพอใจ?',
        text: 'ความพึงพอใจของคุณคือ ' + label + ' และ ' + reasonLabel,
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: 'ยกเลิก',
        confirmButtonText: 'ตกลง',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    method: "GET",
                    url: baseUrl + url,
                    dataType: "json",
                    data: {
                        reason: reason
                    },
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
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.value) {
            swal({
                type: 'success',
                title: 'ขอบคุณที่มาใช้บริการ',
                showConfirmButton: false,
                timer: 3000,
                footer: 'เทศบาลนครอุดรธานี'
            });
            $('.btn-info:hover').css('background-color','#269abc');
        }
    });
}
JS
);
?>