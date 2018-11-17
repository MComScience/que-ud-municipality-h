<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 31/10/2561
 * Time: 10:24
 */
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
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
                    <?php
                    echo '<label class="col-sm-2 control-label">วันที่</label>';
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
                        <hr>
                        <?= Html::submitButton('แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('รีเซ็ต',['/app/report/report-summary'], ['class' => 'btn btn-danger']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <hr>

                <?php
                echo GridView::widget([
                    'dataProvider'=> $dataProvider,
                    //'caption' => isset($data['from_date']) ? Yii::$app->formatter->asDate($data['from_date'],'php:d F Y') : Yii::$app->formatter->asDate('now','php:d F Y'),
                    'panel' => [
                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.$this->title.'</h3>',
                        'type' => 'success',
                        'before' => '',
                        'after' => '',
                        'footer' => ''
                    ],
                    'toolbar' => [
                        '{export}',
                    ],
                    'tableOptions' => ['width' => '100%'],
                    'showPageSummary' => true,
                    /*'beforeHeader' => [
                        [
                            'columns' => [
                                ['content' => '','options' => []],
                                ['content' => '','options' => []],
                                ['content' => 'จำนวน','options' => ['colspan' => 2,'style' => 'text-align: center']]
                            ],
                        ]
                    ],*/
                    'columns' => [
                        [
                            'class' => '\kartik\grid\SerialColumn'
                        ],
                        [
                            'header' => 'แผนก',
                            'attribute' => 'service_group_name',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'group' => true,
                            //'groupedRow'=>true,
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'groupOddCssClass'=>'kv-grouped-row',
                            'groupEvenCssClass'=>'kv-grouped-row',
                            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                                return [
                                    'mergeColumns' => [[1,3]], // columns to merge in summary
                                    'content' => [             // content to show in each summary cell
                                        1 => 'รวม'.$model['service_group_name'],
                                        5 => GridView::F_SUM,
                                        6 => GridView::F_SUM,
                                        7 => GridView::F_SUM,
                                        8 => GridView::F_SUM,
                                        9 => GridView::F_SUM,
                                    ],
                                    'contentFormats' => [      // content reformatting for each summary cell
                                        5 => ['format' => 'number', 'decimals' => 0],
                                        6 => ['format' => 'number', 'decimals' => 0],
                                        7 => ['format' => 'number', 'decimals' => 0],
                                        8 => ['format' => 'number', 'decimals' => 0],
                                        9 => ['format' => 'number', 'decimals' => 0],
                                    ],
                                    'contentOptions' => [      // content html attributes for each summary cell
                                        1 => ['style' => 'font-variant:small-caps;text-align:center'],
                                        5 => ['style' => 'text-align:center'],
                                        6 => ['style' => 'text-align:center'],
                                        7 => ['style' => 'text-align:center'],
                                        8 => ['style' => 'text-align:center'],
                                        9 => ['style' => 'text-align:center'],
                                    ],
                                    // html attributes for group summary row
                                    'options' => ['class' => 'success table-danger','style' => 'font-weight:bold;']
                                ];
                            }
                        ],
                        [
                            'header' => 'ประเภทบริการ',
                            'attribute' => 'service_name',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'group' => true,
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'subGroupOf' => 1,
                            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                                return [
                                    //'mergeColumns' => [[0,1]], // columns to merge in summary
                                    'content' => [             // content to show in each summary cell
                                        2 => 'รวม'.$model['service_name'],
                                        5 => GridView::F_SUM,
                                        6 => GridView::F_SUM,
                                        7 => GridView::F_SUM,
                                        8 => GridView::F_SUM,
                                        9 => GridView::F_SUM,
                                    ],
                                    'contentFormats' => [      // content reformatting for each summary cell
                                        5 => ['format' => 'number', 'decimals' => 0],
                                        6 => ['format' => 'number', 'decimals' => 0],
                                        7 => ['format' => 'number', 'decimals' => 0],
                                        8 => ['format' => 'number', 'decimals' => 0],
                                        9 => ['format' => 'number', 'decimals' => 0],
                                    ],
                                    'contentOptions' => [      // content html attributes for each summary cell
                                        1 => ['style' => 'font-variant:small-caps;text-align:center'],
                                        2 => ['style' => 'text-align:left'],
                                        5 => ['style' => 'text-align:center'],
                                        6 => ['style' => 'text-align:center'],
                                        7 => ['style' => 'text-align:center'],
                                        8 => ['style' => 'text-align:center'],
                                        9 => ['style' => 'text-align:center'],
                                    ],
                                    // html attributes for group summary row
                                    'options' => ['class' => 'success table-danger','style' => 'font-weight:bold;']
                                ];
                            }
                        ],
                        [
                            'header' => 'วันที่',
                            'attribute' => 'date',
                            'hAlign' => 'center',
                            'format' => ['date','php:D d M Y'],
                            'vAlign' => 'middle',
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'groupOddCssClass'=>'kv-grouped-row',
                            'groupEvenCssClass'=>'kv-grouped-row',
                        ],
                        [
                            'header' => 'เวลา',
                            'attribute' => 'range_time',
                            'hAlign' => 'center',
                            'pageSummary' => 'รวม',
                        ],
                        [
                            'header' => 'เวลารอเฉลี่ย(นาที)',
                            'attribute' => 'avg_wait',
                            'hAlign' => 'center',
                            'pageSummary' => true,
                        ],
                        [
                            'header' => 'เวลาพักคิวเฉลี่ย(นาที)',
                            'attribute' => 'avg_hold',
                            'hAlign' => 'center',
                            'pageSummary' => true,
                        ],
                        [
                            'header' => 'เวลาให้บริการเฉลี่ย(นาที)',
                            'attribute' => 'avg_service',
                            'hAlign' => 'center',
                            'pageSummary' => true,
                        ],
                        [
                            'header' => 'รวมเวลา(นาที)',
                            'attribute' => 'sum_time',
                            'hAlign' => 'center',
                            'pageSummary' => true,
                        ],
                        [
                            'header' => 'จำนวนผู้ป่วย',
                            'attribute' => 'count_que',
                            'hAlign' => 'center',
                            'pageSummary' => true,
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
