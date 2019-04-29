<?php
use yii\bootstrap\Tabs;
use yii\helpers\Url;

$action = Yii::$app->controller->action->id;

echo Tabs::widget([
    'items' => [
        [
            'label' => Yii::t('frontend', 'Chart'),
            'url' => $action == 'chart' ? '#' : Url::to(['/app/report/chart']),
            'active' => $action == 'chart' ? true : false,
        ],
        [
            'label' => 'ระยะเวลารอคอย(แบ่งตามช่วงเวลา)',
            'url' => $action == 'index' ? '#' : Url::to(['/app/report/index']),
            'active' => $action == 'index' ? true : false,
        ],
        [
            'label' => 'รายงานการปฎิบัติงาน',
            'url' => $action == 'user' ? '#' : Url::to(['/app/report/user']),
            'active' => $action == 'user' ? true : false,
        ],
        [
            'label' => 'รายงานระยะเวลารอคอย(ภาพรวม)',
            'url' => $action == 'report-summary' ? '#' : Url::to(['/app/report/report-summary']),
            'active' => $action == 'report-summary' ? true : false,
        ],
        [
            'label' => 'รายงานความพึงพอใจ',
            'url' => $action == 'satis' ? '#' : Url::to(['/app/report/satis']),
            'active' => $action == 'satis' ? true : false,
        ],
    ],
    'renderTabContent' => false
]);
?>