<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 18/3/2562
 * Time: 22:24
 */
use homer\sweetalert2\assets\SweetAlert2Asset;
use yii\web\View;
use homer\widgets\Icon;
use homer\widgets\datatables\DataTablesAsset;
use frontend\assets\SocketIOAsset;

$this->title = Yii::t('frontend', 'Calling');
$this->params['breadcrumbs'][] = $this->title;

$css = [
    "@web/css/mobile-menu.css",
    "@web/css/calling-mobile.css",
    "@web/vendor/select2/dist/css/select2.min.css",
    "@web/css/select2-bootstrap.css"
];

foreach ($css as $path) {
    $this->registerCssFile($path, [
        'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    ]);
}

$this->registerJsFile(
    YII_DEBUG ? '@web/js/vue/vue.js' : '@web/js/vue/vue.min.js',
    ['position' => View::POS_HEAD]
);

$this->registerJsFile(
    '@web/vendor/select2/dist/js/select2.full.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

SweetAlert2Asset::register($this);
DataTablesAsset::register($this);
SocketIOAsset::register($this);
?>
<!-- Content -->
<div id="app-mobile-page">
    <div id="right-sidebar" class="animated fadeInRight">
        <div style="padding: 5px;display: contents;text-align: right;">
            <p style="margin: 5px 5px 0px;">
                <button id="sidebar-close" class="right-sidebar-toggle sidebar-button btn btn-danger m-b-md">
                    <i class="pe pe-7s-close"></i>
                </button>
            </p>
        </div>
        <!--<ul class="nav nav-menu-more" style="margin-top: 40px;border-top: 1px solid #e5e5e5;">
            <li><a href="#"><i class="fa fa-circle-o"></i> ตั้งค่า</a></li>
        </ul>-->
        <form>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="select2-profile" class="control-label">เซอร์วิสโปรไฟล์</label>
                    <select2 :options="profileOptions"
                             class="input-lg"
                             id="select2-profile"
                             v-model="profileId"
                             v-on:change-selection="onChangeSelection">
                        <option value="">เซอร์วิสโปรไฟล์</option>
                    </select2>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="select2-profile" class="control-label">จุดบริการ</label>
                    <select2 :options="counterOptions"
                             class="input-lg"
                             id="select2-counter"
                             v-model="counterId"
                             v-on:change-selection="onChangeSelection">
                        <option value="">จุดบริการ</option>
                    </select2>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="body-tab">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="text-center">
                            <h4 class="label-counter-detail">
                                {{ getProfileLabel() }} {{ getCounterLabel() }}
                            </h4>
                            <h1 class="label-queue-number font-extra-bold no-margins text-success">
                                {{ dataOnState.queueNumber }}
                            </h1>
                            <p class="label-personal-info">
                                {{ dataOnState.name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hpanel">
                    <div class="panel-body">
                        <h4 class="label-counter-detail">
                            คิวรอเรียก
                        </h4>
                        <table class="table table-condensed" id="tbl-wait">
                            <thead class="hidden">
                                <tr>
                                    <th>#</th>
                                    <th>คิว</th>
                                    <th>ชื่อ-นามสกุล</th>
    <!--                                <th>กลุ่มบริการ</th>-->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="modalSearch">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form v-on:submit.prevent="onSubmit">
                                    <div class="form-group">
                                        <div class="hint-block text-danger">ค้นหาเฉพาะหมายเลขคิวเท่านั้น</div>
                                        <input type="text"
                                               class="form-control input-lg input-search"
                                               placeholder="ค้นหา..."
                                               autofocus
                                               autocomplete="off"
                                               id="input-search"
                                               ref="input"
                                               v-model="search">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <button type="button" class="btn btn-danger btn-lg btn-block btn-circle-swal" data-dismiss="modal">
                                            <i class="fa fa-close"></i> ปิด
                                        </button>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <button type="button" v-on:click="onSubmit" class="btn btn-success btn-lg btn-block btn-circle-swal" :disabled="!search">
                                            <i class="fa fa-search"></i> ค้นหา
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hpanel">
                    <div class="panel-body panel-body-calling">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 text-right">
                                <p>
                                    <button class="btn btn-sm btn-danger" v-on:click="toggleAction">
                                        <i class="fa fa-angle-double-right" v-if="showAction"></i>
                                        <i class="fa fa-angle-double-left" v-if="!showAction"></i>
                                    </button>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6">
                                <p>
                                    <button class="btn btn-lg btn-info btn-block "
                                            v-on:click="onCallNext"
                                            style="border-radius: 25px!important;"
                                            v-if="showAction">
                                        <i class="fa fa-hand-o-right"></i> คิวถัดไป
                                    </button>
                                </p>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <p>
                                    <button class="btn btn-lg btn-info btn-block"
                                            v-on:click="onRecall"
                                            :disabled="!dataOnState.info"
                                            style="border-radius: 25px!important;"
                                            v-if="showAction">
                                        <i class="fa fa-refresh"></i> เรียกซ้ำ
                                    </button>
                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6">
                                <p>
                                    <button class="btn btn-lg btn-info btn-block"
                                            v-on:click="onHold"
                                            :disabled="!dataOnState.info"
                                            style="border-radius: 25px!important;"
                                            v-if="showAction">
                                        <i class="fa fa-hand-paper-o"></i> พักคิว
                                    </button>
                                </p>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <p>
                                    <button class="btn btn-lg btn-info btn-block"
                                            v-on:click="onEnd"
                                            :disabled="!dataOnState.info"
                                            style="border-radius: 25px!important;"
                                            v-if="showAction">
                                        <i class="fa fa-check-circle-o"></i> เสร็จสิ้น
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-2" class="tab-pane">
            <div class="body-tab">
                <div class="hpanel">
                    <div class="panel-body">
                        <table class="table table-condensed" id="tbl-calling">
                            <thead class="hidden">
                                <tr>
                                    <th>#</th>
                                    <th>คิว</th>
                                    <th>ชื่อ-นามสกุล</th>
    <!--                                <th>กลุ่มบริการ</th>-->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-3" class="tab-pane">
            <div class="body-tab">
                <div class="hpanel">
                    <div class="panel-body">
                        <table class="table table-condensed" id="tbl-hold">
                            <thead class="hidden">
                                <tr>
                                    <th>#</th>
                                    <th>คิว</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <!--                                <th>กลุ่มบริการ</th>-->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->render('mobile-menu'); ?>
</div>
<!-- Content -->

<?php

$this->registerJsFile(
    '@web/js/page-mobile.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
// Scripts
$this->registerJs(<<<JS
    
JS
);
?>
