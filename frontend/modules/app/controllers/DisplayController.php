<?php

namespace frontend\modules\app\controllers;

use frontend\modules\app\classes\AppQuery;
use frontend\modules\app\models\TbCounterService;
use frontend\modules\app\models\TbDisplay;
use frontend\modules\app\models\TbService;
use homer\utils\CoreUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\modules\app\traits\ModelTrait;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;

class DisplayController extends \yii\web\Controller
{
    use ModelTrait;

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
                    [
                        'allow' => true,
                        'actions' => ['index', 'view','data-display','data-lastq','data-hold','all'],
                        'roles' => ['?'],
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
        $displays = TbDisplay::find()->where(['display_status' => 1])->all();
        return $this->render('index', [
            'displays' => $displays,
        ]);
    }

    public function actionView($id)
    {
        $this->layout = '@frontend/views/layouts/display.php';
        $counters = [];
        $config = $this->findModelDisplay($id);
        $config->display_css = strip_tags($config['display_css']);
        $service_ids = CoreUtility::string2Array($config['service_id']);
        $counter_service_ids = CoreUtility::string2Array($config['counter_service_id']);
        $modelCounter = TbCounterService::find()->where(['counter_service_type_id' => $counter_service_ids])->all();
        foreach ($modelCounter as $item) {
            $counters[] = (string)$item['counter_service_id'];
        }
        return $this->render('view', [
            'config' => $config,
            'service_ids' => $service_ids,
            'counters' => $counters,
        ]);
    }

    public function actionDataDisplay()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $config = $request->post('config', []);
            $items = [];

            $service_ids = CoreUtility::string2Array($config['service_id']);
            $counter_service_ids = CoreUtility::string2Array($config['counter_service_id']);

            $lastCall = AppQuery::getDataQueCurrentCall($service_ids, $counter_service_ids);//คิวที่กำลังเรียก
            $rows = AppQuery::getDataQueDisplay($service_ids, $counter_service_ids, $lastCall, $config);

            if ($rows && $config['que_column_length'] > 1) {
                //เรียงข้อมูลใหม่ เรียงตามหมายเลข ช่องบริการ
                ArrayHelper::multisort($rows, ['counter_service_call_number', 'counter_service_call_number'], [SORT_ASC, SORT_ASC]);
                //group ข้อมูลเคาท์เตอร์
                $counter_call_number = ArrayHelper::map($rows, 'counter_service_call_number', 'counter_service_call_number');
                foreach ($counter_call_number as $number) {

                    $query = AppQuery::getDataQueDisplayByCounterNumber($service_ids, $counter_service_ids, $lastCall, $number, $config);

                    $tempArr = [];
                    $data = [];
                    $que_nums = ArrayHelper::getColumn($query, 'que_num');
                    $class = '';
                    foreach ($que_nums as $qnum) {
                        $tempArr[] = Html::tag('span', $qnum . ' |', ['class' => $qnum]);
                        $data[] = $qnum;
                        $class = $qnum;
                    }
                    $items[] = [
                        'que_number' => count($tempArr) == 1 ? str_replace('|','',implode(" ", $tempArr)) : implode(" ", $tempArr),
                        'counter_number' => Html::tag('span', $config['text_th_right'].' '.$number, ['class' => $class]),
                        'data' => $data,
                        'counter_service_call_number' => '-'
                    ];
                }

                #ถ้าไม่มีข้อมูลคิว
                if (count($items) < $config['page_length']) {
                    $items = ArrayHelper::merge($items, $this->renderDefaultData($config, count($items)));
                }
            } else {
                $items = $this->renderItems($rows, $config);
                #ถ้าไม่มีข้อมูลคิว
                if (count($rows) < $config['page_length']) {
                    $items = ArrayHelper::merge($items, $this->renderDefaultData($config));
                }
            }
            return [
                'data' => $items,
            ];
        }
    }

    public function actionDataLastq()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $config = $request->post('config', []);
            $items = [];

            $service_ids = CoreUtility::string2Array($config['service_id']);
            $counter_service_ids = CoreUtility::string2Array($config['counter_service_id']);

            $lastCall = AppQuery::getDataQueCurrentCall($service_ids, $counter_service_ids);//คิวที่กำลังเรียก

            $models = TbService::find()->where(['service_id' => $service_ids])->all();
            $mapPrefix = ArrayHelper::map($models, 'service_prefix', 'service_prefix');
            foreach ($mapPrefix as $prefix) {
                $rows = AppQuery::getDataDisplayByPrefix($service_ids, $counter_service_ids, $lastCall, $prefix);
                if ($rows) {
                    $items[] = [
                        'service_prefix' => $prefix,
                        'que_num' => $rows['que_num'],
                    ];
                } else {
                    $items[] = [
                        'service_prefix' => $prefix,
                        'que_num' => '-',
                    ];
                }
            }
            if (count($items) < $config['page_length']) {
                for ($x = count($items); $x < $config['page_length']; $x++) {
                    $arr = [
                        'service_prefix' => '-',
                        'que_num' => '-',
                    ];
                    $items[] = $arr;
                }
            }
            return ['data' => $items];
        }
    }

    public function actionDataHold()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $config = $request->post('config', []);
            $service_ids = CoreUtility::string2Array($config['service_id']);
            $counter_service_ids = CoreUtility::string2Array($config['counter_service_id']);
            $items = [];
            $rows = AppQuery::getDataQueHoldDisplay($service_ids, $counter_service_ids);
            if (count($rows) == 0) {
                $items[] = [
                    'text' => '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                    ' . $config['text_hold'] . '</div>',
                    'que_number' => '-',
                    'data' => []
                ];
            } else {
                $que_numbers = ArrayHelper::getColumn($rows, 'que_num');
                $items[] = [
                    'text' => '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                    ' . $config['text_hold'] . '</div>',
                    'que_number' => count($que_numbers) > 0 ? Html::tag('marquee', implode(" | ", $que_numbers), ['direction' => 'left','id' => date('His'),'class' => 'marquee-hold']) : '-',
                    'data' => $que_numbers
                ];
            }
            return [
                'data' => $items
            ];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    protected function renderDefaultData($config, $x = 0)
    {
        $items = [];
        for ($x; $x < $config['page_length']; $x++) {
            $arr = [
                'que_number' => '-',
                'counter_number' => '-',
                'data' => [],
                'counter_service_call_number' => '-'
            ];
            $items[] = $arr;
        }
        return $items;
    }

    protected function renderItems($rows, $config)
    {
        $items = [];
        foreach ($rows as $row) {
            $arr = [
                'que_number' => Html::tag('span', $row['que_num'], ['class' => $row['que_num']]),
                'counter_number' => Html::tag('span', $config['text_th_right'].' '.$row['counter_service_call_number'], ['class' => $row['que_num']]),
                'data' => [$row['que_num']],
                'counter_service_call_number' => $row['counter_service_call_number'],
                'DT_RowAttr' => ['data-key' => $row['que_num']],
                'DT_RowId' => $row['que_num'],
            ];
            $items[] = $arr;
        }
        return $items;
    }

    public function actionAll()
    {
        $this->layout = '@frontend/views/layouts/display.php';
        $displays = TbDisplay::find()->where(['display_status' => 1])->all();
        $options = [];
        foreach($displays as $config){
            $display_css = str_replace("#tb-display","#tb-display".$config['display_ids'],$config['display_css']);
            $config->display_css = strip_tags($display_css);
            $service_ids = CoreUtility::string2Array($config['service_id']);
            $counter_service_ids = CoreUtility::string2Array($config['counter_service_id']);
            $modelCounter = TbCounterService::find()->where(['counter_service_type_id' => $counter_service_ids])->all();
            $counters = [];
            foreach ($modelCounter as $item) {
                $counters[] = (string)$item['counter_service_id'];
            }
            $options[] = [
                'config' => $config,
                'services' => $service_ids,
                'counters' => $counters,
            ];
        }
        
        return $this->render('all', [
            'options' => $options,
        ]);
    }

}
