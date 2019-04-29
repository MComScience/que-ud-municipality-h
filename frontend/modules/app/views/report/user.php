<?php
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;

$this->title = 'รายงานสรุปการปฏิบัติงาน';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hpanel">
    <?php echo $this->render('_tabs'); ?>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
                <div class="form-group">
                    <?= Html::label('วันที่','', ['class'=>'col-sm-2 control-label']) ?>
                    <div class="col-sm-4">
                        <?php
                        echo $form->field($model, 'date_range')->widget(DateRangePicker::classname(), [
                            'presetDropdown'=>true,
                            'hideInput'=>true,
                            'startAttribute' => 'from_date',
                            'endAttribute' => 'to_date',
                            'pluginOptions'=>[
                                'locale'=>['format' => 'Y-MM-DD'],
                            ],
                            'startInputOptions' => ['value' => isset($posted['from_date']) ? $posted['from_date'] : Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
                            'endInputOptions' => ['value' => isset($posted['to_date']) ? $posted['to_date'] : Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
                        ])->label(false);
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <hr>
                        <?= Html::submitButton('แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('รีเซ็ต',['/app/report/chart'], ['class' => 'btn btn-danger']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <hr>

                <?php
                $title = isset($model['from_date']) ? $this->title.' ('.Yii::$app->formatter->asDate($model['from_date'], 'php:d F Y').' - '.Yii::$app->formatter->asDate($model['to_date'], 'php:d F Y').')' : $this->title;
                echo GridView::widget([
                    'dataProvider'=> $dataProvider,
                    'caption' => $title,
                    'panel' => [
                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.$title.'</h3>',
                        'type' => 'success',
                        'before' => '',
                        'after' => '',
                        'footer' => false
                    ],
                    'toolbar' => [
                        '{export}',
                    ],
                    /*
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
                                'marginTop' => 20,
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
                                                    'content' => '',
                                                    'font-size' => 16,
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
                                                    'content' => '',
                                                    'font-size' => 16,
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
                            'filename' => 'รายงานระยะเวลารอคอย',
                            'alertMsg' => 'The EXCEL export file will be generated for download.',
                            'options' => ['title' => 'Microsoft Excel 95+'],
                            'mime' => 'application/vnd.ms-excel',
                            'config' => [
                                'worksheet' => 'ExportWorksheet',
                                'cssFile' => '',
                            ],
                        ],
                    ],*/
                    'beforeHeader' => [
                        [
                            'columns' => [
                                ['content' => '','options' => []],
                                ['content' => '','options' => []],
                                ['content' => 'จำนวน','options' => ['colspan' => 2,'style' => 'text-align: center']]
                            ],
                        ]
                    ],
                    'columns' => [
                        [
                            'class' => '\kartik\grid\SerialColumn'
                        ],
                        [
                            'header' => 'วันที่',
                            'attribute' => 'day',
                            'hAlign' => 'center',
                            'format' => ['date','php:d F Y'],
                            'group' => true,
                            'groupedRow'=>true,
                            'contentOptions' => ['style' => 'text-align: left;'],
                            'groupOddCssClass'=>'kv-grouped-row',
                            'groupEvenCssClass'=>'kv-grouped-row',
                        ],
                        [
                            'header' => 'ชื่อ-นามสกุล',
                            'attribute' => 'name',
                            'hAlign' => 'left',
                            'format' => 'raw',
                            'value' => function($model){
                                return $model['username'].', '.$model['name'];
                            }
                        ],
                        // [
                        //     'header' => 'ออกบัตรคิว',
                        //     'attribute' => 'count1',
                        //     'hAlign' => 'center',
                        //     'format' => 'raw'
                        // ],
                        [
                            'header' => 'เรียกคิว',
                            'attribute' => 'count2',
                            'hAlign' => 'center',
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true,
                    'showPageSummary' => true
                ]);
                ?>
            </div>
        </div>
    </div>
</div>