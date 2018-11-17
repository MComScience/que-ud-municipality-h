<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 16/11/2561
 * Time: 10:22
 */
use homer\widgets\Table;
use kartik\icons\Icon;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<div class="panel-body" style="border-top: 1px solid #e4e5e7;">
    <?php
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover', 'id' => 'tb-calling', 'cellpadding' => 1, 'cellspacing' => 1],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => Yii::t('frontend', 'Prefix'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Que Number'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'FirstName').'-'.Yii::t('frontend', 'LastName'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Group'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Call Time'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Actions'), 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'afterHeader' => [
            [
                'columns' => [
                    ['content' => '', 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Prefix'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => '', 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'FirstName').'-'.Yii::t('frontend', 'LastName'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Service Group'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => '', 'options' => ['style' => 'text-align: center;']],
                    ['content' => '', 'options' => ['style' => 'text-align: center;','colspan' => 2]],
                ],
            ],
        ],
        'datatableOptions' => [
            "clientOptions" => [
                "dom" => "<'row'<'col-sm-5'f><'col-sm-2 toolbar-calling'><'col-sm-5'l B>><'row'<'col-xs-12 col-sm-12 col-md-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "ajax" => [
                    "url" => Url::base(true) . "/app/calling/data-que-calling",
                    "type" => "POST",
                    "data" => ["data" => $modelProfile,'formData' => $formData],
                    "complete" => new JsExpression('function(qXHR, textStatus ){
                        var api = $(\'#tb-calling\').DataTable();
                        setTimeout(function(){
                            dtFunc.initSelect2(api,[
                                {col: 1,title: "'.Yii::t('frontend', 'Prefix').'"},
                                {col: 2,title: "'.Yii::t('frontend', 'Que Number').'"},
                                {col: 3,title: "'.Yii::t('frontend', 'FirstName').'-'.Yii::t('frontend', 'LastName').'"},
                                {col: 4,title: "'.Yii::t('frontend', 'Service Group').'"},
                            ]);
                        },200);
                        api.buttons(0).processing( false );
                    }')
                ],
                "responsive" => true,
                "autoWidth" => false,
                "deferRender" => true,
                "order" => [[ 1, 'asc' ]],
                "lengthMenu" => [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "language" => [
                    "sSearch" => " _INPUT_ ",
                    "searchPlaceholder" => "ค้นหา...",
                    "sLengthMenu" => "_MENU_",
                ],
                "columns" => [
                    ["data" => null,"defaultContent" => "", "className" => "text-center", "render" => new JsExpression ('function ( data, type, row, meta ) {
                        return (meta.row + 1);
                    }')],
                    ["data" => "service_prefix", "className" => "text-center","orderable" => false],
                    ["data" => "que_num_badge", "className" => "text-center","orderable" => false],
                    ["data" => "pt_name","orderable" => false],
                    ["data" => "service_group_name","orderable" => false],
                    ["data" => "service_name","orderable" => false],
                    ["data" => "call_timestp", "className" => "text-center"],
                    ["data" => "actions", "className" => "text-center nowrap", "orderable" => false],
                ],
                "drawCallback" => new JsExpression('function ( settings ) {
                    var api = this.api();
                    var count  = api.data().count();
                    $(".count-call").html(count);
                    //group
                    var rows = api.rows( {page:\'current\'} ).nodes();
                    var last=null;
                    api.column(5, {page:\'current\'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                \'<tr class="warning"><td colspan="\'+api.columns().data().length+\'">\'+group+\'</td></tr>\'
                            );
         
                            last = group;
                        }
                    } );
                }'),
                "initComplete" => new JsExpression('function ( settings ) {
                    var api = this.api();
                    $("div.toolbar-calling").html(\'<span class="badge badge-warning text-center"><i class="pe-7s-ticket"></i> '.Yii::t('frontend', 'Queue Call').'</span>\');
                }'),
                "columnDefs" => [
                    [ "visible" => false, "targets" => [5] ]
                ],
                "buttons" => [
                    [
                        "text" => Icon::show('refresh').Yii::t('frontend', 'Reload'),
                        "action" =>  new JsExpression('function ( e, dt, node, config ) {
                            this.processing( true );
                            dt.ajax.reload();
                        }'),
                    ],
                    [
                        "text" => Icon::show('list').Yii::t('frontend', 'Show/Hide Columns'),
                        "extend" => 'colvis',
                    ],
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
