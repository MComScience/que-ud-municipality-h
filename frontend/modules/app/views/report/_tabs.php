<?php
use yii\bootstrap\Tabs;
use yii\helpers\Url;

$action = Yii::$app->controller->action->id;

echo Tabs::widget([
    'items' => [
        [
            'label' => Yii::t('frontend', 'Chart'),
            'url' => Url::to(['/app/report/chart']),
            'active' => $action == 'chart' ? true : false,
        ],
        [
            'label' => 'ระยะเวลารอคอย(แบ่งตามช่วงเวลา)',
            'url' => Url::to(['/app/report/index']),
            'active' => $action == 'index' ? true : false,
        ],
        [
            'label' => 'รายงานระยะเวลารอคอย(ภาพรวม)',
            'url' => Url::to(['/app/report/report-summary']),
            'active' => $action == 'report-summary' ? true : false,
        ],
    ],
    'renderTabContent' => false
]);
?>