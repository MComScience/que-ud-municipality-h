<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 21:17
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
        'tableOptions' => ['class' => 'table table-hover', 'id' => 'tb-display'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#', 'options' => ['style' => 'text-align: center;width: 35px;']],
                    ['content' => Yii::t('frontend', 'Display Name'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Display Status'), 'options' => ['style' => 'text-align: center;']],
                    ['content' => Yii::t('frontend', 'Actions'), 'options' => ['style' => 'text-align: center;']],
                ],
            ],
        ],
        'datatableOptions' => [
            "clientOptions" => [
                "dom" => "<'row'<'col-sm-6'f><'col-sm-6'l B>><'row'<'col-xs-12 col-sm-12 col-md-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "ajax" => [
                    "url" => Url::base(true) . "/app/settings/data-display",
                    "type" => "GET",
                    "complete" => new JsExpression('function(jqXHR, textStatus) {
                        var table = $(\'#tb-display\').DataTable();
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
                    "sSearch" => "ค้นหา: _INPUT_ ".Html::a(Icon::show('plus').' '.Yii::t('frontend','Add'),['/app/settings/create-display'],['class' => 'btn btn-success'])
                ],
                "pageLength" => 10,
                "columns" => [
                    ["data" => "index", "className" => "text-center"],
                    ["data" => "display_name"],
                    ["data" => "display_status","className" => "text-center"],
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
                    dtFunc.initConfirm("#tb-display");
                    $("a.activity-copy").on("click",function(event){
                        event.preventDefault();
                        QueSetting.dupplicate(this);
                    });
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
<?php
$this->registerJs(<<<JS
    QueSetting = {
        dupplicate: function(elm){
            swal({
                title: 'Duplicate?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "GET",
                        url: $(elm).attr("href"),
                        dataType: "json",
                        success: function(response){
                            dt_tbdisplay.ajax.reload();
                        },
                        error: function( jqXHR, textStatus, errorThrown){
                            swal({title: 'Error...!',text: errorThrown ,type: 'error'});
                        }
                    });
                }
            });
        }
    };
JS
);
?>