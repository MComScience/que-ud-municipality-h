<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 17/11/2561
 * Time: 15:45
 */
use homer\widgets\Table;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->render('_assets',['config' => $config,'service_ids' => $service_ids,'counters' => $counters]);

$this->title = 'จอแสดงผล'.$config['display_name'];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 border-right">
            <?= $config['text_top_left']; ?>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center border-right clock-display" style="font-size: 45px;color: #fff">
            <span class="time__hours"><?= Yii::$app->formatter->asDate('now','php:H') ?></span> :
            <span class="time__min"><?= Yii::$app->formatter->asDate('now','php:i') ?></span>
        </div>
        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
            <?= $config['text_top_right']; ?>
        </div>
    </div>
    <div class="row" style="margin-right: 5px;">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="container">
                <?php
                echo Table::widget([
                    'tableOptions' => ['class' => 'table','id' => 'tb-display'],
                    'beforeHeader' => [
                        [
                            'columns' => [
                                ['content' => $config['text_th_left'], 'options' => ['style' => 'text-align: center;width: 60%;','class' => 'th-left']],
                                ['content' => $config['text_th_right'], 'options' => ['style' => 'text-align: center;width: 20%;','class' => 'th-right']],
                                ['content' => '', 'options' => []],
                            ],
                        ],
                    ],
                    'datatableOptions' => [
                        "clientOptions" => [
                            "ajax" => [
                                "url" => Url::base(true)."/app/display/data-display",
                                "type" => "POST",
                                "data" => ['config' => $config],
                            ],
                            "dom" => "t",
                            "responsive" => true,
                            "autoWidth" => false,
                            "deferRender" => true,
                            "ordering" => false,
                            "pageLength" => empty($config['page_length']) ? -1 : $config['page_length'],
                            "columns" => [
                                ["data" => "que_number","defaultContent" => "", "className" => "text-center td-left","orderable" => false],
                                ["data" => "counter_number","defaultContent" => "", "className" => "text-center td-right","orderable" => false],
                                ["data" => "data","orderable" => false, "visible" => false],
                            ],
                            "language" => [
                                "loadingRecords" => "Loading...",
                                "emptyTable" => "ไม่มีข้อมูลคิว"
                            ]
                        ],
                        'clientEvents' => [
                            'error.dt' => 'function ( e, settings, techNote, message ){
                            e.preventDefault();
                            console.warn("error message",message);
                        }'
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <?php
            echo Table::widget([
                'tableOptions' => ['class' => 'table','id' => 'tb-lastq'],
                'beforeHeader' => [
                    [
                        'columns' => [
                            ['content' => $config['text_th_lastq_left'], 'options' => ['style' => 'text-align: center;width: 50%;','class' => 'th-left']],
                            ['content' => $config['text_th_lastq_right'], 'options' => ['style' => 'text-align: center;width: 50%;','class' => 'th-right']],
                        ],
                    ],
                ],
                'datatableOptions' => [
                    "clientOptions" => [
                        "ajax" => [
                            "url" => Url::base(true)."/app/display/data-lastq",
                            "type" => "POST",
                            "data" => ['config' => $config],
                        ],
                        "dom" => "t",
                        "responsive" => true,
                        "autoWidth" => false,
                        "deferRender" => true,
                        "ordering" => false,
                        "pageLength" => empty($config['page_length']) ? -1 : $config['page_length'],
                        "columns" => [
                            ["data" => "service_prefix","defaultContent" => "", "className" => "text-center td-left","orderable" => false],
                            ["data" => "que_num","defaultContent" => "", "className" => "text-center td-right","orderable" => false],
                        ],
                        "language" => [
                            "loadingRecords" => "Loading...",
                            "emptyTable" => "ไม่มีข้อมูลคิว"
                        ]
                    ],
                    'clientEvents' => [
                        'error.dt' => 'function ( e, settings, techNote, message ){
                            e.preventDefault();
                            console.warn("error message",message);
                        }'
                    ],
                ],
            ]);
            ?>
        </div>
    </div>

<?php if ($config['show_que_hold'] == 1) : ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="container">
                <?php
                echo Table::widget([
                    'tableOptions' => ['class' => 'table','id' => 'tb-hold'],
                    'columns' => [
                        [
                            ['content' => '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                            คิวที่เรียกไปแล้ว
                            </div>', 'options' => ['class' => 'td-left','style' => 'width: 40%;']],
                            ['content' => '-', 'options' => ['class' => 'td-right','style' => 'width: 60%;']],
                        ],
                    ],
                    'datatableOptions' => [
                        "clientOptions" => [
                            "ajax" => [
                                "url" => Url::base(true)."/app/display/data-hold",
                                "type" => "POST",
                                "data" => ['config' => $config],
                            ],
                            "dom" => "t",
                            "responsive" => true,
                            "autoWidth" => false,
                            "deferRender" => true,
                            "ordering" => false,
                            "pageLength" => 1,
                            "columns" => [
                                ["data" => "text","defaultContent" => '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                                '.$config['text_hold'].'</div>', "className" => "text-center td-left","orderable" => false, "width" => "40%"],
                                ["data" => "que_number","defaultContent" => "", "className" => "text-center td-right","orderable" => false],
                            ],
                            "language" => [
                                "loadingRecords" => "กำลังโหลดข้อมูล...",
                                "emptyTable" => "ไม่มีข้อมูลคิว"
                            ],
                            'initComplete' => new JsExpression ('
                            function () {
                                var api = this.api();
                                $("#tb-hold thead").hide();
                            }
                        '),
                        ],
                        'clientEvents' => [
                            'error.dt' => 'function ( e, settings, techNote, message ){
                            e.preventDefault();
                            console.warn("error message",message);
                        }'
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$this->registerJsFile(
    '@web/js/display.js',
    [
        'depends' => [\yii\web\JqueryAsset::className(), \homer\assets\HomerAsset::className()]
    ]
);
?>