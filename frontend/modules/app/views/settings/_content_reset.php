<?php

use homer\widgets\Table;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<div class="panel-body">
    <?php
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover table-striped','id' => 'tb-que-list'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                    ['content' => 'กลุ่มบริการ','options' => ['style' => 'text-align: center;']],
                    ['content' => 'ชื่อบริการ', 'options' => ['style' => 'text-align: center;']],
                    ['content' => 'เวลา', 'options' => ['style' => 'text-align: center;']],
                    ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                    ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'datatableOptions' => [
            "clientOptions" => [
                "dom" => "<'row'<'col-sm-6'f><'col-sm-6'l B>><'row'<'col-xs-12 col-sm-12 col-md-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "ajax" => [
                    "url" => Url::base(true)."/app/kiosk/data-que-list",
                    "type" => "GET",
                ],
                "responsive" => true,
                "autoWidth" => false,
                "deferRender" => true,
                "pageLength" => 50,
                "language" => [
                    "loadingRecords" => "กำลังโหลดข้อมูล...",
                    "lengthMenu" => "_MENU_",
                    "sSearch" => "ค้นหา: _INPUT_ ".Html::button(Icon::show('refresh').'Reset',['class' => 'btn btn-danger on-reset'])
                ],
                "columns" => [
                    ["data" => "index", "className" => "text-center"],
                    ["data" => "que_num_badge","className" => "text-center"],
                    ["data" => "service_group_name"],
                    ["data" => "service_name"],
                    ["data" => "created_at","className" => "text-center"],
                    ["data" => "que_status_name","className" => "text-center"],
                    ["data" => "actions", "className" => "text-center", "orderable" => false],
                ],
                "drawCallback" => new JsExpression('function ( settings ) {
                    var api = this.api();
                    var count  = api.data().count();
                    $("#count-qdata").html(count);
                    dtFunc.initConfirm("#tb-que-list");
                }'),
                "columnDefs" => [
                    [ "visible" => false, "targets" => [6] ]
                ]
            ],
            'clientEvents' => [
                'error.dt' => 'function ( e, settings, techNote, message ){
                    e.preventDefault();
                    swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
                }'
            ]
        ],
    ]);
    ?>
</div>
<?php
$this->registerJs(<<<JS
    $('button.on-reset').on('click',function(e){
        swal({
            title: 'ยืนยัน?',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'รีเซ็ต',
            cancelButtonText: "ยกเลิก",
            allowEscapeKey: false,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        type: 'POST',
                        url: baseUrl+"/app/settings/reset-data-que",
                        success: function (response, textStatus, jqXHR) {
                            dt_tbquelist.ajax.reload();
                            resolve();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal({
                                type: "error",
                                title: textStatus,
                                text: errorThrown,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        dataType: "json"
                    });
                });
            },
        }).then((result) => {
            if (result.value) {
                swal.close();
            }
        });
    });
JS
);
?>
