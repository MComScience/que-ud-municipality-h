<?php
use homer\highcharts\Highcharts;
use yii\web\JsExpression;
$service_group_id = $model['service_group_id'];
?>

<div class="panel-body">
    <div class="row content-count<?= $service_group_id ?>">
        <?php foreach ($model['services'] as $service) : ?>
            <div class="col-md-3">
                <div class="hpanel hbggreen">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h3><?= $service['service_name'] ?></h3>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-user fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="font-light"><?= $service['count'] ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 border-right border-bottom border-left border-top">
            <?php
            echo Highcharts::widget([
                'id' => 'chart1_'.$service_group_id,
                'options' => [
                    'chart' => [
                        'plotBackgroundColor' => null,
                        'plotBorderWidth' => null,
                        'plotShadow' => false,
                        'type' => 'pie'
                    ],
                    'title' => [
                        'text' => 'ร้อยละของจำนวนผู้ป่วย แยกตามประเภทผู้ป่วย'
                    ],
                    'tooltip' => [
                        'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true,
                                'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                'style' => [
                                    'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.contrastTextColor) || \'black\' ')
                                ]
                            ]
                        ]
                    ],
                    'series' => [[
                        'name' => 'คิดเป็น',
                        'colorByPoint' => true,
                        'data' => $model['pieData']
                    ]],
                    'credits' => ['enabled' => false],
                ],
                'scripts' => [
                    'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                    'modules/exporting', // adds Exporting button/menu to chart
                    'modules/export-data',
                    //'themes/sand-signika' 
                ],
            ]);
            ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 border-right border-bottom border-left border-top">
            <?php
            echo Highcharts::widget([
                'id' => 'chart2_'.$service_group_id,
                'options' => [
                    'chart' => [
                        'type' => 'bar'
                    ],
                    'title' => [
                        'text' => 'จำนนวนผู้ป่วย แยกตามประเภทผู้ป่วย'
                    ],
                    'xAxis' => [
                        'categories' => $model['dataChart2']['categories'],
                        'title' => [
                            'text' => null
                        ]
                    ],
                    'yAxis' => [
                        'min' => 0,
                        'title' => [
                            'text' => 'จำนวน',
                            'align' => 'high'
                        ],
                        'labels' => [
                            'overflow' => 'justify'
                        ]
                    ],
                    'tooltip' => [
                        'valueSuffix' => ' คิว'
                    ],
                    'plotOptions' => [
                        'bar' => [
                            'dataLabels' => [
                                'enabled' => true
                            ]
                        ]
                    ],
                    'series' => $model['dataChart2']['series'],
                    'credits' => ['enabled' => false],
                ],
                'scripts' => [
                    'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                    'modules/exporting', // adds Exporting button/menu to chart
                    'modules/export-data',
                    //'themes/sand-signika' 
                ],
            ]);
            ?>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 border-right border-bottom border-left border-top">
            <?php
            echo Highcharts::widget([
                'id' => 'chart3_'.$service_group_id,
                'options' => [
                    'chart' => [
                        'type' => 'column'
                    ],
                    'title' => [
                        'text' => 'จำนวนผู้ป่วยที่มาใช้บริการและจำนวนคิวที่จ่ายได้ แบ่งตามช่วงเวลา'
                    ],
                    'subtitle' => [
                        'text' => 'สามารถคลิกที่แท่งกราฟเพื่อดูจำนวนแยกตามประเภทบริการได้'
                    ],
                    'xAxis' => [
                        'type' => 'category',
                        'labels' => [
                            'rotation' => 45
                        ],
                    ],
                    'yAxis' => [
                        'min' => 0,
                        'title' => [
                            'text' => 'จำนวน',
                        ],
                    ],
                    'tooltip' => [
                        'headerFormat' => '<span style="font-size: 11px">{series.name}</span><br>',
                        'pointFormat' => '<span style="color: {point.color}">{point.name}</span>: <b>จำนวน {point.y} คิว</b><br/>',
                    ],
                    'plotOptions' => [
                        'series' => [
                            'borderWidth' => 0,
                            'dataLabels' => [
                                'enabled' => true,
                                'format' => '{point.y}'
                            ],
                        ]
                    ],
                    'series' => $model['dataChartRangeTime']['series'],
                    'legend' => ['enabled' => false],
                    'drilldown' => [
                        'series' => $model['dataChartRangeTime']['subseries']
                    ],
                    'credits' => ['enabled' => false],
                ],
                'scripts' => [
                    'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                    'modules/exporting', // adds Exporting button/menu to chart
                    'modules/export-data',
                    'modules/drilldown',
                ],
            ]);
            ?>
        </div>
    </div>
</div>