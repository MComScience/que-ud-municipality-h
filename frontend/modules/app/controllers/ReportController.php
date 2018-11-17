<?php

namespace frontend\modules\app\controllers;

use frontend\modules\app\models\Report;
use frontend\modules\app\models\TbQueData;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbServiceGroup;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ReportController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $model = new Report();
        $data = [];
        $posted = $request->post('Report', []);
        if ($model->load($request->post())) {
            $from_date = empty($posted['from_date']) ? substr($posted['date_range'], 0, 10) : $posted['from_date'];
            $to_date = empty($posted['to_date']) ? substr($posted['date_range'], 13, 22) : $posted['to_date'];
            $times = $posted['times'];
            $model->from_date = $from_date;
            $model->to_date = $to_date;
            $periods = new \DatePeriod(
                new \DateTime($from_date),
                new \DateInterval('P1D'),
                new \DateTime(date('Y-m-d', strtotime('+1 day', strtotime($to_date))))
            );
            foreach ($periods as $key => $value) {
                $date = $value->format('Y-m-d');
                if (count($times) > 0) {
                    foreach ($times as $t) {
                        $timeRange = $t['time_start'] . '-' . $t['time_end'];
                        $time_start = $date . ' ' . $t['time_start'];
                        $time_end = $date . ' ' . $t['time_end'];
                        $query = (new \yii\db\Query())
                            ->select([
                                'tb_que_data.que_ids',
                                'tb_que_data.que_num',
                                'tb_que_data.created_at',
                                'tb_caller_data.call_timestp',
                                'tb_counter_service.counter_service_name',
                                'tb_service.service_name',
                                'tb_service_profile.service_profile_name',
                                'MINUTE(TIMEDIFF(tb_que_data.created_at, tb_caller_data.call_timestp)) AS t_wait',
                            ])
                            ->from('tb_que_data')
                            ->innerJoin('tb_caller_data', 'tb_caller_data.que_ids = tb_que_data.que_ids')
                            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller_data.counter_service_id')
                            ->innerJoin('tb_service', 'tb_service.service_id = tb_que_data.service_id')
                            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller_data.service_profile_id')
                            ->andWhere(['between', 'tb_que_data.created_at', $time_start, $time_end])
                            ->all();
                        if ($query) {
                            foreach ($query as $item) {
                                $arr = [
                                    'que_num' => $item['que_num'],
                                    'created_at' => $item['created_at'],
                                    'call_timestp' => $item['call_timestp'],
                                    'service_name' => $item['service_name'],
                                    't_wait' => $item['t_wait'],
                                    'time_range' => '<p class="skip-export">(' . $date . ')</p> ' . $timeRange,
                                    'day' => $date
                                ];
                                $data[] = $arr;
                            }
                        } else {
                            $arr = [
                                'que_num' => '',
                                'created_at' => null,
                                'call_timestp' => null,
                                'service_name' => '',
                                't_wait' => '',
                                'time_range' => '<p class="skip-export">(' . $date . ')</p> ' . $timeRange,
                                'day' => $date
                            ];
                            $data[] = $arr;
                        }
                    }
                }
            }
        } else {
            $times = [
                ['time_start' => '06:00', 'time_end' => '07:00'],
                ['time_start' => '07:00', 'time_end' => '08:00'],
                ['time_start' => '08:00', 'time_end' => '09:00'],
                ['time_start' => '09:00', 'time_end' => '10:00'],
                ['time_start' => '10:00', 'time_end' => '11:00'],
                ['time_start' => '11:00', 'time_end' => '12:00'],
                ['time_start' => '12:00', 'time_end' => '13:00'],
                ['time_start' => '13:00', 'time_end' => '14:00'],
                ['time_start' => '14:00', 'time_end' => '15:00'],
                ['time_start' => '15:00', 'time_end' => '16:00'],
            ];
            $model->times = $times;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'posted' => $posted,
        ]);
    }

    public function actionChart()
    {
        $request = Yii::$app->request;
        $model = new Report();
        $data = [];
        $days = [];
        $drilldown = [];
        $arrcount = [];
        $series3 = [];
        $times = [];
        $posted = $request->post('Report', []);
        $serviceGroups = TbServiceGroup::find()->all();
        if ($model->load($request->post())) {
            $from_date = empty($posted['from_date']) ? substr($posted['date_range'], 0, 10) : $posted['from_date'];
            $to_date = empty($posted['to_date']) ? substr($posted['date_range'], 13, 22) : $posted['to_date'];
            foreach ($serviceGroups as $serviceGroup) {
                $services = TbService::find()->where(['service_status' => 1, 'service_group_id' => $serviceGroup['service_group_id']])->all();
                $sumAll = TbQueData::find()->where(['service_group_id' => $serviceGroup['service_group_id']])->andWhere(['between', 'created_at', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])->count();
                $arr = [];
                foreach ($services as $service) {
                    $count = TbQueData::find()
                        ->where(['service_id' => $service['service_id'], 'service_group_id' => $serviceGroup['service_group_id']])
                        ->andWhere(['between', 'created_at', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                        ->count();

                    $arr[] = [
                        'service_name' => $service['service_name'],
                        'count' => $count,
                        'bg' => 'hbggreen'
                    ];
                }
                $sumStatus1 = TbQueData::find()->where(['service_group_id' => $serviceGroup['service_group_id'], 'que_status_id' => 1])->andWhere(['between', 'created_at', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])->count();
                $sumStatus2 = TbQueData::find()->where(['service_group_id' => $serviceGroup['service_group_id'], 'que_status_id' => [2, 3]])->andWhere(['between', 'created_at', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])->count();
                $sumStatus4 = TbQueData::find()->where(['service_group_id' => $serviceGroup['service_group_id'], 'que_status_id' => 4])->andWhere(['between', 'created_at', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])->count();

                $arr[] = [
                    'service_name' => 'คิวที่ไม่ได้จ่าย',
                    'count' => $sumStatus1,
                    'bg' => 'hbgorange'
                ];
                $arr[] = [
                    'service_name' => 'คิวที่ค้างจ่าย',
                    'count' => $sumStatus2,
                    'bg' => 'hbgorange'
                ];
                $arr[] = [
                    'service_name' => 'คิวที่จ่ายได้',
                    'count' => $sumStatus4,
                    'bg' => 'hbgorange'
                ];
                $arr[] = [
                    'service_name' => 'รวมคิวทั้งหมด',
                    'count' => $sumAll,
                    'bg' => 'hbgnavyblue'
                ];
                //chart
                $series = [];
                $seriesData = [];
                $categories = [];
                $pieData = [];

                foreach ($services as $service) {
                    $count = TbQueData::find()
                        ->where(['service_id' => $service['service_id'], 'service_group_id' => $serviceGroup['service_group_id']])
                        ->andWhere(['between', 'created_at', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                        ->count();
                    $y = ($count > 0 && $sumAll > 0) ? ($count / $sumAll) * 100 : 0;
                    $pieData[] = [
                        'name' => $service['service_name'], 'y' => $y
                    ];

                    $categories = ArrayHelper::merge($categories, [$service['service_name']]);
                    $seriesData = ArrayHelper::merge($seriesData, [intval($count)]);
                }
                //
                $period = new \DatePeriod(
                    new \DateTime($from_date),
                    new \DateInterval('P1D'),
                    new \DateTime(date('Y-m-d', strtotime('+1 day', strtotime($to_date))))
                );

                foreach ($period as $key => $value) {
                    $day = $value->format('Y-m-d');
                    $days = ArrayHelper::merge($days, [$day]);
                    $array = [];
                    $arrcount = [];
                    $series2 = [];
                    $subdrilldown = [];
                    $serviceTotal = [];

                    $sub_time = '';
                    $h = 5;
                    for ($i = 1; $i <= 12; $i++) {
                        $date1 = new \DateTime($day . ' ' . $h . ':00:00');
                        $date1->modify('+1 hour');
                        $hour1 = $date1->format('H:i:s') . PHP_EOL;

                        $date2 = new \DateTime($day . ' ' . $h . ':00:00');
                        $date2->modify('+2 hour');
                        $hour2 = $date2->format('H:i:s') . PHP_EOL;

                        $h++;

                        $start = $day . ' ' . $hour1;
                        $end = $day . ' ' . $hour2;
                        $count = TbQueData::find()
                            ->where(['between', 'created_at', $start, $end])
                            ->andWhere(['service_group_id' => $serviceGroup['service_group_id']])
                            ->count();
                        $time = substr($hour1, 0, 5) . '-' . substr($hour2, 0, 5);
                        $array = ArrayHelper::merge($array, [
                            't_' . $h => intval($count),
                            'day' => $day,
                        ]);
                        $arrcount[] = intval($count);
                        $sub_time = 'sub_' . $day . $time;
                        $series2[] = ['name' => $time, 'y' => intval($count), 'drilldown' => $sub_time];

                        foreach ($services as $service) {
                            $count = TbQueData::find()
                                ->where(['between', 'created_at', $start, $end])
                                ->andWhere(['service_id' => $service['service_id'], 'service_group_id' => $serviceGroup['service_group_id']])
                                ->count();
                            $subdrilldown[] = [
                                $service['service_name'], intval($count)
                            ];
                        }

                        $drilldown[] = ['id' => $sub_time, 'data' => $subdrilldown];
                        unset($subdrilldown);
                    }

                    $series3[] = [
                        "name" => $day,
                        "y" => array_sum($arrcount),
                        "drilldown" => $day
                    ];
                    $drilldown[] = [
                        'name' => $day,
                        'id' => $day,
                        'data' => $series2
                    ];
                    $times[] = $array;
                }
                $series[] = [
                    'name' => 'คิวทั้งหมด',
                    'colorByPoint' => true,
                    'data' => $seriesData,
                ];
                $dataChart2 = [
                    'series' => $series,
                    'categories' => $categories,
                ];
                $series3 = [
                    [
                        'name' => 'วันที่',
                        "colorByPoint" => true,
                        'data' => $series3
                    ]
                ];
                $data[] = [
                    'service_group_id' => $serviceGroup['service_group_id'],
                    'service_group_name' => $serviceGroup['service_group_name'],
                    'services' => $arr,
                    'pieData' => $pieData,
                    'dataChart2' => $dataChart2,
                    'dataChart3' => ['series' => $series3, "drilldown" => [
                        "series" => $drilldown
                    ]],
                ];

            }
        }
        return $this->render('chart', [
            'posted' => $posted,
            'model' => $model,
            'data' => $data,
        ]);
    }

    public function actionReportSummary()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $modelReport = new Report();
        $result = [];
        $data = $request->post('Report');
        if ($modelReport->load($request->post())){
            $from_date = empty($posted['from_date']) ? substr($data['date_range'], 0, 10) : $data['from_date'];
            $to_date = empty($posted['to_date']) ? substr($data['date_range'], 13, 22) : $data['to_date'];
            //$response->format = \yii\web\Response::FORMAT_JSON;
            $rowsServices = (new \yii\db\Query())
                ->select([
                    'tb_service_group.service_group_name',
                    'tb_service.service_id',
                    'tb_service.service_name',
                    'tb_service.service_group_id'
                ])
                ->from('tb_service')
                ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
                ->where(['tb_service.service_status' => 1])
                ->all();
            $rangeTimes = [
                ['start' => '08:00', 'end' => '10:00'],
                ['start' => '10:00', 'end' => '12:00'],
                ['start' => '12:00', 'end' => '14:00'],
                //['start' => '13:00', 'end' => '14:30'],
                ['start' => '14:00', 'end' => '16:00'],
            ];

            $period = new \DatePeriod(
                new \DateTime($from_date),
                new \DateInterval('P1D'),
                new \DateTime(date('Y-m-d', strtotime('+1 day', strtotime($to_date))))
            );
            foreach ($period as $key => $value) {
                $day = $value->format('Y-m-d');
                foreach ($rowsServices as $rowsService) {
                    foreach ($rangeTimes as $rangeTime) {
                        $start = $day.' '. $rangeTime['start'];
                        $end = $day.' '. $rangeTime['end'];
                        $modelQue = TbQueData::find()
                            ->where([
                                'service_id' => $rowsService['service_id'],
                                'service_group_id' => $rowsService['service_group_id'],
                            ])
                            ->andWhere(['between', 'created_at', $start, $end])
                            ->all();
                        $avgWaitArr = [];
                        $avgHoldArr = [];
                        $avgServiceArr = [];
                        foreach ($modelQue as $model) {
                            if(!empty($model['status_times'])){
                                $decodeTime = Json::decode($model['status_times']);
                                $mapTimes = ArrayHelper::map($decodeTime, 'id', 'time');
                                $status1 = ArrayHelper::getValue($mapTimes, 1, null);
                                $status2 = ArrayHelper::getValue($mapTimes, 2, null);
                                $status3 = ArrayHelper::getValue($mapTimes, 3, null);
                                $status4 = ArrayHelper::getValue($mapTimes, 4, null);
                                $status5 = ArrayHelper::getValue($mapTimes, 5, null);
                                //คำนวนเวลารอเฉลี่ย
                                if ($status1 !== null && $status2 !== null) {
                                    $avgWaitArr[] = $this->diffDate($status1, $status2, '%I');
                                }
                                //เวลาพักคิวเฉลี่ย
                                if ($status2 !== null && $status3 !== null) {
                                    $avgHoldArr[] = $this->diffDate($status2, $status3, '%I');
                                }
                                //เวลาให้บริการ
                                if ($status1 !== null && $status4 !== null) {
                                    $avgServiceArr[] = $this->diffDate($status1, $status4, '%I');
                                }
                            }
                        }
                        $avg_wait = intval($this->getAvg($avgWaitArr));
                        $avg_hold = intval($this->getAvg($avgHoldArr));
                        $avg_service = intval($this->getAvg($avgServiceArr));
                        $result[] = ArrayHelper::merge($rowsService, [
                            'range_time' => $rangeTime['start'] . '-' . $rangeTime['end'],
                            'avg_wait' => $avg_wait,
                            'avg_hold' => $avg_hold,
                            'avg_service' => $avg_service,
                            'sum_time' =>  intval($avg_wait + $avg_hold + $avg_service),
                            'count_que' => count($modelQue),
                            'date' => $day
                        ]);
                    }
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);
        return $this->render('report-summary',[
            'dataProvider' => $dataProvider,
            'modelReport' => $modelReport,
            'data' => $data,
        ]);
    }

    protected function diffDate($d1, $d2, $f = '%H ชม., %I น., %S วินาที')
    {
        $dt1 = new \DateTime($d1);
        $dt2 = new \DateTime($d2);
        $interval = $dt1->diff($dt2);
        return $interval->format($f);
    }

    protected function getAvg($array)
    {
        return count($array) > 0 ? array_sum($array) / count($array) : 0;
    }

}
