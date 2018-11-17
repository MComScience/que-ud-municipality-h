<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/10/2561
 * Time: 12:53
 */
namespace common\components;

use frontend\modules\app\models\TbServiceGroup;
use Yii;
use yii\base\Component;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\models\TbService;
use yii\helpers\ArrayHelper;

class ChartBuilder extends Component
{

    public $service_group_id;

    private $_services;

    private $_sumAll;

    public function init()
    {
        parent::init();
        $this->_services = TbService::find()->where(['service_status' => 1,'service_group_id' => $this->service_group_id])->all();
        $this->_sumAll = TbQue::find()->where(['service_group_id' => $this->service_group_id])->count();
    }

    public function getDataChart1()
    {
        $pieData = [];
        $services = $this->_services;
        $sumAll = $this->_sumAll;

        foreach ($services as $service) {
            $count = $this->countQueService($service['service_id']);
            $y = ($count > 0 && $sumAll > 0) ? ($count / $sumAll) * 100 : 0;
            $pieData[] = [
                'name' => $service['service_name'], 'y' => $y
            ];
        }
        return $pieData;
    }

    public function getDataChart2()
    {
        $series = [];
        $seriesData = [];
        $categories = [];
        $services = $this->_services;

        foreach ($services as $service) {
            $count = $this->countQueService($service['service_id']);
            $categories = ArrayHelper::merge($categories, [$service['service_name']]);
            $seriesData = ArrayHelper::merge($seriesData, [intval($count)]);
        }
        $series[] = [
            'name' => 'คิวทั้งหมด',
            'colorByPoint' => true,
            'data' => $seriesData,
        ];
        return [
            'series' => $series,
            'categories' => $categories,
        ];
    }

    public function getDataChartServiceGroup()
    {
        $serviceGroups = TbServiceGroup::find()->all();
        $subseries = [];
        $series = [];
        foreach ($serviceGroups as $group) {
            $count = $this->counQueServiceGroup($group['service_group_id']);
            $series[] = [
                "name" => $group['service_group_name'],
                "y" => intval($count),
                "drilldown" => $group['service_group_id']
            ];
            $services = TbService::find()->where(['service_group_id' => $group['service_group_id']])->all();
            $drilldown = [];
            foreach ($services as $service) {
                $count = TbQue::find()->where([
                    'service_group_id' => $group['service_group_id'],
                    'service_id' => $service['service_id'],
                ])->count();
                $drilldown[] = [
                    ($service['service_prefix'] . ': ' . $service['service_name']),
                    intval($count)
                ];
            }
            $subseries[] = [
                'name' => $group['service_group_name'],
                'id' => $group['service_group_id'],
                'data' => $drilldown
            ];
            unset($drilldown);
        }
        return [
            'series' => [['name' => 'ช่วงเวลา', 'colorByPoint' => true, 'data' => $series]],
            'subseries' => $subseries,
        ];
    }

    public function getDataChartRangeTime()
    {
        $today = Yii::$app->formatter->asDate('now','php:Y-m-d');
        $startTime = 5;
        $services = $this->_services;
        $subseries = [];
        $series = [];
        for ($x = 1; $x <= 12; $x++) {
            $d1 = new \DateTime($today . ' ' . $startTime . ':00:00');
            $d1->modify('+1 hour');
            $h1 = $d1->format('H:i:s') . PHP_EOL;

            $d2 = new \DateTime($today . ' ' . $startTime . ':00:00');
            $d2->modify('+2 hour');
            $h2 = $d2->format('H:i:s') . PHP_EOL;

            $startTime++;

            $start = $today . ' ' . $h1;
            $end = $today . ' ' . $h2;

            $count = TbQue::find()->where(['between', 'created_at', $start, $end])->andWhere(['service_group_id' => $this->service_group_id])->count();
            $t = substr($h1, 0, 5) . '-' . substr($h2, 0, 5);
            $series[] = [
                "name" => $t,
                "y" => intval($count),
                "drilldown" => 'chart1-'.$t
            ];

            $drilldown = [];
            foreach ($services as $service) {
                $count = TbQue::find()
                    ->where(['between', 'created_at', $start, $end])
                    ->andWhere([
                        'service_id' => $service['service_id'],
                        'service_group_id' => $this->service_group_id
                    ])
                    ->count();
                
                $drilldown[] = [
                    ($service['service_prefix'] . ': ' . $service['service_name']), intval($count)
                ];
            }
            $subseries[] = [
                'name' => $t,
                'id' => 'chart1-'.$t,
                'data' => $drilldown
            ];

            unset($drilldown);
        }
        return [
            'series' => [['name' => 'คิวทั้งหมด', 'colorByPoint' => true, 'data' => $series]],
            'subseries' => $subseries,
        ];
    }

    public function getDataChartRangeTime2()
    {
        $today = Yii::$app->formatter->asDate('now','php:Y-m-d');
        $startTime = 5;
        $services = $this->_services;
        $subseries = [];
        $series = [];
        for ($x = 1; $x <= 12; $x++) {
            $d1 = new \DateTime($today . ' ' . $startTime . ':00:00');
            $d1->modify('+1 hour');
            $h1 = $d1->format('H:i:s') . PHP_EOL;

            $d2 = new \DateTime($today . ' ' . $startTime . ':00:00');
            $d2->modify('+2 hour');
            $h2 = $d2->format('H:i:s') . PHP_EOL;

            $startTime++;

            $start = $today . ' ' . $h1;
            $end = $today . ' ' . $h2;

            $count = TbQue::find()
                ->where(['between', 'created_at', $start, $end])
                ->andWhere(['service_group_id' => $this->service_group_id,'que_status_id' => 4]) //เฉพาะคิวที่เสร็จสิ้น
                ->count();
            
            $t = substr($h1, 0, 5) . '-' . substr($h2, 0, 5);
            $series[] = [
                "name" => $t,
                "y" => intval($count),
                "drilldown" => 'chart2-'.$t
            ];

            $drilldown = [];
            foreach ($services as $service) {
                $count = TbQue::find()
                    ->where(['between', 'created_at', $start, $end])
                    ->andWhere([
                        'service_id' => $service['service_id'],
                        'service_group_id' => $this->service_group_id,
                        'que_status_id' => 4
                    ])
                    ->count();
                
                $drilldown[] = [
                    ($service['service_prefix'] . ': ' . $service['service_name']), intval($count)
                ];
            }
            $subseries[] = [
                'name' => $t,
                'id' => 'chart2-'.$t,
                'data' => $drilldown
            ];

            unset($drilldown);
        }
        return [
            'series' => [['name' => 'คิวที่จ่ายไปแล้ว', 'colorByPoint' => true, 'data' => $series]],
            'subseries' => $subseries,
        ];
    }

    private function countQueService($service_id)
    {
        return TbQue::find()->where(['service_id' => $service_id,'service_group_id' => $this->service_group_id])->count();
    }

    private function counQueServiceGroup($service_group_id)
    {
        return TbQue::find()->where(['service_group_id' => $service_group_id])->count();
    }
}