<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;
use homer\user\models\User;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

$this->title = 'รายงาน';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hpanel">
    <?php echo $this->render('_tabs'); ?>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ชื่อ</label>
                        <div class="col-sm-4">
                            <?=
                            $form->field($modelReport, 'user')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                                'options' => [
                                    'placeholder' => 'เลือกชื่อ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'theme' => Select2::THEME_BOOTSTRAP,
                            ])->label(false);
                            ?>
                        </div>
                        <?php
                        echo '<label class="col-sm-1 control-label">วันที่</label>';
                        ?>
                        <div class="col-sm-4">
                            <?php
                            echo $form->field($modelReport, 'date_range')->widget(DateRangePicker::classname(), [
                                'presetDropdown'=>true,
                                'hideInput'=>true,
                                'startAttribute' => 'from_date',
                                'endAttribute' => 'to_date',
                                'pluginOptions'=>[
                                    'locale'=>['format' => 'Y-MM-DD'],
                                ],
                                'startInputOptions' => ['value' => isset($data['from_date']) ? $data['from_date'] : Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
                                'endInputOptions' => ['value' => isset($data['to_date']) ? $data['to_date'] : Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <?= Html::submitButton('แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('รีเซ็ต',['/app/report/satis'], ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                <hr>
                <?php
                echo GridView::widget([
                    'dataProvider'=> $dataProvider,
                    'panel' => [
                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.$this->title.'</h3>',
                        'type' => 'default',
                        'before' => '',
                        'after' => '',
                        'footer' => ''
                    ],
                    'toolbar' => [
                        '{export}',
                    ],
                    'tableOptions' => ['style' => 'width: 100%'],
                    'showPageSummary' => false,
                    'beforeFooter' => [
                        [
                            'columns' => [
                                ['content' => 'เหตุผล', 'options' => ['colspan' => 6, 'style' => 'text-align: center']],
                            ]
                        ]
                    ],
                    'afterFooter' => ArrayHelper::merge([
                        [
                            'columns' => [
                                ['content' => '', 'options' => ['style' => 'text-align: center; width: 16.6%']],
                                ['content' => 'พูดจาไม่สุภาพ', 'options' => ['style' => 'text-align: center; width: 16.6%']],
                                ['content' => 'ไม่ใส่ใจบริการ', 'options' => ['style' => 'text-align: center; width: 16.6%']],
                                ['content' => 'ทำงานไม่ตรงเวลา', 'options' => ['style' => 'text-align: center; width: 16.6%']],
                                ['content' => 'ล่าช้า', 'options' => ['style' => 'text-align: center; width: 16.6%']],
                                ['content' => 'รวม', 'options' => ['style' => 'text-align: center; width: 16.6%']],
                                /* ['content' => yii\grid\GridView::widget([
                                    'dataProvider'=> $dataProviderReason,
                                    'layout' => '{items}',
                                    'tableOptions' => ['style' => 'width: 100%', 'class' => 'table table-bordered table-striped'],
                                    'showFooter' => false,
                                    'columns' => [
                                        [
                                            'header' => 'ความพึงพอใจ',
                                            'attribute' => 'label',
                                            'contentOptions' => ['style' => 'text-align: center;width: 20%;'],
                                        ],
                                        [
                                            'header' => 'พูดจาไม่สุภาพ',
                                            'attribute' => 'reason_count1',
                                            'contentOptions' => ['style' => 'text-align: center;width: 20%;'],
                                        ],
                                        [
                                            'header' => 'ไม่ใส่ใจบริการ',
                                            'attribute' => 'reason_count2',
                                            'contentOptions' => ['style' => 'text-align: center;width: 20%;'],
                                        ],
                                        [
                                            'header' => 'ทำงานไม่ตรงเวลา',
                                            'attribute' => 'reason_count3',
                                            'contentOptions' => ['style' => 'text-align: center;width: 20%;'],
                                        ],
                                        [
                                            'header' => 'ล่าช้า',
                                            'attribute' => 'reason_count4',
                                            'contentOptions' => ['style' => 'text-align: center;width: 20%;'],
                                        ],
                                    ]
                                ]), 
                                'options' => ['colspan' => 2, 'style' => 'width: 100%']],*/
                            ],
                        ],
                    ],$afterFooter),
                    'showFooter' => true,
                    'resizableColumns' => false,
                    'columns' => [
                        [
                            'header' => 'ระดับความพึงพอใจ',
                            'attribute' => 'label',
                            'hAlign' => 'left',
                            'vAlign' => 'middle',
                            'width' => '50%',
                            'headerOptions' => ['style' => 'width: 40%;text-align: center;', 'colspan' => 3],
                            'contentOptions' => ['colspan' => 3],
                        ],
                        // [
                        //     'header' => 'คะแนนความพึงพอใจ',
                        //     'attribute' => 'value',
                        //     'hAlign' => 'center',
                        //     'vAlign' => 'middle',
                        //     'headerOptions' => ['colspan' => 2, 'style' => 'width: 20%',],
                        //     'contentOptions' => ['colspan' => 2],
                        //     //'pageSummary' => 'รวมผู้มาใช้บริการ',
                        // ],
                        /*[
                            'header' => '',
                            'attribute' => 'reasons',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'format' => 'raw',
                            'value' => function($model) use ($dataProviderReason){
                                return GridView::widget([
                                    'dataProvider'=> $dataProviderReason,
                                    'layout' => '{items}',
                                    'columns' => [
                                        [
                                            'header' => 'ความพึงพอใจ',
                                            'attribute' => 'label',
                                            'hAlign' => 'center',
                                            'vAlign' => 'middle',
                                        ],
                                        [
                                            'header' => 'พูดจาไม่สุภาพ',
                                            'attribute' => 'reason_count1',
                                            'hAlign' => 'center',
                                            'vAlign' => 'middle',
                                        ],
                                        [
                                            'header' => 'ไม่ใส่ใจบริการ',
                                            'attribute' => 'reason_count2',
                                            'hAlign' => 'center',
                                            'vAlign' => 'middle',
                                        ],
                                        [
                                            'header' => 'ทำงานไม่ตรงเวลา',
                                            'attribute' => 'reason_count3',
                                            'hAlign' => 'center',
                                            'vAlign' => 'middle',
                                        ],
                                        [
                                            'header' => 'ล่าช้า',
                                            'attribute' => 'reason_count4',
                                            'hAlign' => 'center',
                                            'vAlign' => 'middle',
                                        ],
                                    ]
                                ]);
                            },
                        ],*/
                        [
                            'header' => 'รวมจำนวน',
                            'attribute' => 'count',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '50%',
                            'headerOptions' => ['style' => 'width: 40%','colspan' => 3],
                            'contentOptions' => ['colspan' => 3],
                            //'pageSummary' => true,
                        ],
                    ],
                    'export' => [
                        'fontAwesome' => true,
                        'showConfirmAlert' => false
                    ],
                    'captionOptions' => ['style' => 'text-align: center;font-size:18px;border-bottom: 1px solid #ddd;'],
                    'exportConfig' => [
                        GridView::PDF => [
                            'label' => 'PDF',
                            //'icon' => 'file-pdf-o',
                            'iconOptions' => ['class' => 'text-danger'],
                            'showHeader' => true,
                            'showPageSummary' => true,
                            'showFooter' => true,
                            'showCaption' => true,
                            'filename' => 'รายงานระยะเวลารอคอย',
                            'alertMsg' => 'The PDF export file will be generated for download.',
                            'options' => ['title' => 'Portable Document Format'],
                            'mime' => 'application/pdf',
                            'config' => [
                                'mode' => 'UTF-8',
                                'format' => 'A4-L',
                                'destination' => 'D',
                                'marginTop' => 30,
                                'marginBottom' => 20,
                                'cssInline' => '.kv-wrap{padding:20px;}' .
                                    '.kv-align-center{text-align:center;}' .
                                    '.kv-align-left{text-align:left;}' .
                                    '.kv-align-right{text-align:right;}' .
                                    '.kv-align-top{vertical-align:top!important;}' .
                                    '.kv-align-bottom{vertical-align:bottom!important;}' .
                                    '.kv-align-middle{vertical-align:middle!important;}' .
                                    '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                                    '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                                    '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                                'methods' => [
                                    'SetHeader' => [
                                        [
                                            'odd' => [
                                                'L' => [
                                                    'content' => '',
                                                    'font-size' => 8,
                                                    'color' => '#333333',
                                                ],
                                                'C' => [
                                                    'content' => '<h4>รายงานผลการประเมินความพึงพอใจ</h4><h4>เทศบาลนครอุดรธานี</h4><h4>ตั้งแต่วันที่ '.$reportHeader.'</h4>',
                                                    'font-size' => 24,
                                                    'color' => '#333333',
                                                ],
                                                'R' => [
                                                    'content' => 'พิมพ์วันที่'. ': ' . Yii::$app->formatter->asDate('now', 'php:d/m/Y'),
                                                    'font-size' => 10,
                                                    'color' => '#333333',
                                                ],
                                            ],
                                            'even' => [
                                                'L' => [
                                                    'content' => '',
                                                    'font-size' => 8,
                                                    'color' => '#333333',
                                                ],
                                                'C' => [
                                                    'content' => 'รายงานผลการประเมินความพึงพอใจ',
                                                    'font-size' => 24,
                                                    'color' => '#333333',
                                                ],
                                                'R' => [
                                                    'content' => 'พิมพ์วันที่'. ': ' . Yii::$app->formatter->asDate('now', 'php:d/m/Y'),
                                                    'font-size' => 10,
                                                    'color' => '#333333',
                                                ],
                                            ]
                                        ],
                                    ],
                                    'SetFooter' => [
                                        [
                                            'odd' => [
                                                'L' => [
                                                    'content' => Yii::$app->name,
                                                    'font-size' => 10,
                                                    'font-style' => 'B',
                                                    'color' => '#999999',
                                                ],
                                                'R' => [
                                                    'content' => '[ {PAGENO} ]',
                                                    'font-size' => 10,
                                                    'font-style' => 'B',
                                                    'font-family' => 'serif',
                                                    'color' => '#333333',
                                                ],
                                                'line' => false,
                                            ],
                                            'even' => [
                                                'L' => [
                                                    'content' => 'โรงพยาบาลพิมาย',
                                                    'font-size' => 8,
                                                    'font-style' => 'B',
                                                    'color' => '#999999',
                                                ],
                                                'R' => [
                                                    'content' => '[ {PAGENO} ]',
                                                    'font-size' => 10,
                                                    'font-style' => 'B',
                                                    'font-family' => 'serif',
                                                    'color' => '#333333',
                                                ],
                                                'line' => false,
                                            ]
                                        ],
                                    ],
                                ],
                                'options' => [
                                    'title' => '',
                                    'subject' => 'PDF export generated by kartik-v/yii2-grid extension',
                                    'keywords' => 'grid, export, yii2-grid, pdf',
                                ],
                                'contentBefore' => '',
                                'contentAfter' => '',
                            ]
                        ],
                        GridView::EXCEL => [
                            'label' => 'Excel',
                            //'icon' => 'file-excel-o',
                            'iconOptions' => ['class' => 'text-success'],
                            'showHeader' => true,
                            'showPageSummary' => true,
                            'showFooter' => true,
                            'showCaption' => true,
                            'filename' => 'รายงานความพึงพอใจ',
                            'alertMsg' => 'The EXCEL export file will be generated for download.',
                            'options' => ['title' => 'Microsoft Excel 95+'],
                            'mime' => 'application/vnd.ms-excel',
                            'config' => [
                                'worksheet' => 'ExportWorksheet',
                                'cssFile' => '',
                            ],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs(<<<JS
    $('.kv-table-footer').remove();
    $('#w2').css('width', '100%');
JS
);
?>