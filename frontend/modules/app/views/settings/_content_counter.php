<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/11/2561
 * Time: 22:17
 */
use homer\widgets\Table;
use yii\web\JsExpression;
use yii\helpers\Url;
use homer\widgets\Icon;
use yii\helpers\Html;
?>
<div class="panel-body">
    <?php
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover', 'id' => 'tb-counter-service'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => Yii::t('frontend', 'Counter Service Type Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Counter Service Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Counter Service Call Number'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Group Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Sound Service'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Sound Number'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Counter Service Status'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Actions'), 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'datatableOptions' => [
            "clientOptions" => [
                "dom" => "<'row'<'col-sm-6'f><'col-sm-6'l B>><'row'<'col-xs-12 col-sm-12 col-md-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "ajax" => [
                    "url" => Url::base(true) . "/app/settings/data-counter-service",
                    "type" => "GET",
                    "complete" => new JsExpression('function(jqXHR, textStatus) {
                        var table = $(\'#tb-counter-service\').DataTable();
                        $(table.buttons(0)[0].node).button(\'reset\');
                        table.buttons(0).processing( false );
                    }')
                ],
                "deferRender" => true,
                "responsive" => true,
                "autoWidth" => false,
                "processing" => true,
                "lengthMenu" => [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "language" => [
                    "loadingRecords" => "กำลังโหลดข้อมูล...",
                    "lengthMenu" => "_MENU_",
                    "sSearch" => "ค้นหา: _INPUT_ ".Html::a(Icon::show('plus').' '.Yii::t('frontend','Add'),['/app/settings/create-counter-service'],['role' => 'modal-remote','class' => 'btn btn-success'])
                ],
                "pageLength" => 10,
                "columns" => [
                    ["data" => "index", "className" => "text-center"],
                    ["data" => "counter_service_type_name"],
                    ["data" => "counter_service_name"],
                    ["data" => "counter_service_call_number","className" => "text-center"],
                    ["data" => "service_group_name"],
                    ["data" => "sound_name1"],
                    ["data" => "sound_name2"],
                    ["data" => "counter_service_status","className" => "text-center"],
                    ["data" => "actions", "className" => "text-center actions", "orderable" => false],
                ],
                "buttons" => [
                    [
                        "text" => Icon::show('refresh') . 'Reload',
                        "action" => new JsExpression('function ( e, dt, node, config ) {
                            this.processing( true );
                            $(node).button(\'loading\');
                            dt.ajax.reload();
                        }'),
                        "init" => new JsExpression('function ( dt, node, config ) {
                            var that = this;
                            $(node).removeClass("dt-button").addClass("btn btn-sm btn-success btn-outline");
                        }')
                    ],
                ],
                "drawCallback" => new JsExpression('function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:"current"} ).nodes();
                    var columns = api.columns().nodes();
                    var last=null;
                    api.column(1, {page:"current"} ).data().each( function ( group, i ) {
                        var data = api.rows(i).data();
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                \'<tr class="warning"><td colspan="\'+columns.length+\'"><b>จุดบริการ:</b> \'+group+\' <a href="/app/settings/update-counter-service?id=\'+data[0].counter_service_type_id+\'" class="btn btn-xs btn-success" role="modal-remote"><i class="fa fa-plus"></i> เพิ่มรายการ</a> </td></tr>\'
                            );
                            last = group;
                        }
                    } );
                    dtFunc.initConfirm("#tb-counter-service");
                }'),
                "columnDefs" => [
                    ["visible" => false, "targets" => 1],
                ],
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
