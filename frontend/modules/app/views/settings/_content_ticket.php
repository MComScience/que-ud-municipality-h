<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 20:46
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
        'tableOptions' => ['class' => 'table table-hover', 'id' => 'tb-ticket'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => Yii::t('frontend', 'Hospital Name Th'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Hospital Name En'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Barcode Type'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Ticket Status'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Actions'), 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'datatableOptions' => [
            "clientOptions" => [
                "dom" => "<'row'<'col-sm-6'f><'col-sm-6'l B>><'row'<'col-xs-12 col-sm-12 col-md-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "ajax" => [
                    "url" => Url::base(true) . "/app/settings/data-ticket",
                    "type" => "GET",
                    "complete" => new JsExpression('function(jqXHR, textStatus) {
                        var table = $(\'#tb-ticket\').DataTable();
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
                    "sSearch" => "ค้นหา: _INPUT_ ".Html::a(Icon::show('plus').' '.Yii::t('frontend','Add'),['/app/settings/create-ticket'],['class' => 'btn btn-success'])
                ],
                "pageLength" => 10,
                "columns" => [
                    ["data" => "index", "className" => "text-center"],
                    ["data" => "hos_name_th"],
                    ["data" => "hos_name_en"],
                    ["data" => "barcode_type"],
                    ["data" => "ticket_status", "className" => "text-center"],
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
                    dtFunc.initConfirm("#tb-ticket");
                }'),
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