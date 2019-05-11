<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 22:07
 */

use homer\widgets\Table;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

?>

<div class="panel-body">
    <?php
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover table-striped', 'id' => 'tb-que-list', 'width' => '100%'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => Yii::t('frontend', 'Que Number'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'HN'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'FirstName').'-'.Yii::t('frontend', 'LastName'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Group'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Counter Service'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Ticket Time'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Status'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Actions'), 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'afterHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => Yii::t('frontend', 'Que Number'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'HN'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'FirstName').'-'.Yii::t('frontend', 'LastName'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Group'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Counter Service'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Ticket Time'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Status'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Actions'), 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'datatableOptions' => [
            "clientOptions" => [
                "dom" => "<'row'<'col-sm-6'f><'col-sm-6'l B>><'row'<'col-xs-12 col-sm-12 col-md-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "ajax" => [
                    "url" => Url::base(true) . "/app/kiosk/data-que-list",
                    "type" => "GET",
                    "complete" => new JsExpression('function(qXHR, textStatus ){
                        var api = $(\'#tb-que-list\').DataTable();
                        setTimeout(function(){
                            dtFunc.initSelect2(api,[
                                {col: 1,title: "หมายเลขคิว"},
                                {col: 2,title: "HN"},
                                {col: 3,title: "ชื่อ-นามสกุล"},
                                {col: 4,title: "กลุ่มบริการ"},
                                {col: 5,title: "ชื่อบริการ"},
                                {col: 6,title: "จุดบริการ"},
                                {col: 7,title: "เวลาออกบัตรคิว"},
                                {col: 8,title: "สถานะ"},
                            ]);
                        },200);
                        api.buttons(0).processing( false );
                    }')
                ],
                "responsive" => true,
                "language" => [
                    "sSearch" => " _INPUT_ ",
                    "searchPlaceholder" => "ค้นหา...",
                ],
                "autoWidth" => false,
                "deferRender" => true,
                "columns" => [
                    ["data" => "index", "className" => "text-center"],
                    ["data" => "que_num_badge", "className" => "text-center", "orderable" => false],
                    ["data" => "que_hn", "className" => "text-center", "orderable" => false],
                    ["data" => "pt_name", "orderable" => false],
                    ["data" => "service_group_name", "orderable" => false],
                    ["data" => "service_name", "orderable" => false],
                    ["data" => "counter_service_name", "orderable" => false],
                    ["data" => "created_at", "className" => "text-center", "orderable" => false],
                    ["data" => "que_status_name", "className" => "text-center", "orderable" => false],
                    ["data" => "actions", "className" => "text-center", "orderable" => false],
                ],
                "drawCallback" => new JsExpression('function ( settings ) {
                    var api = this.api();
                    var count  = api.data().count();
                    $("#count-qdata").html(count);
                    dtFunc.initConfirm("#tb-que-list");
                }'),
                "columnDefs" => [
                    [ "visible" => false, "targets" => [2]]
                ],
                "lengthMenu" => [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
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