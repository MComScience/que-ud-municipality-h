<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/11/2561
 * Time: 20:21
 */
namespace frontend\modules\app\classes;

use frontend\modules\app\models\TbCounterService;
use homer\utils\CoreUtility;
use Yii;
use yii\data\ArrayDataProvider;
use homer\widgets\table\DataColumn;
use homer\widgets\table\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use homer\widgets\Icon;
use frontend\modules\app\models\TbSound;
use frontend\modules\app\models\TbService;

class AppQuery
{
    public static function getDataServiceGroup()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_service_group.service_group_id',
                'tb_service_group.service_group_name',
                'tb_service_group.service_group_status',
                'tb_service.service_id',
                'tb_service.service_name',
                'tb_service.print_template_id',
                'tb_service.print_copy_qty',
                'tb_service.service_prefix',
                'tb_service.service_numdigit',
                'tb_service.service_status',
                'tb_ticket.*',
            ])
            ->from('tb_service_group')
            ->leftJoin('tb_service', 'tb_service.service_group_id = tb_service_group.service_group_id')
            ->leftJoin('tb_ticket', 'tb_ticket.ticket_ids = tb_service.print_template_id')
            ->orderBy('tb_service_group.service_group_id ASC')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'service_group_id',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'service_group_id',
                ],
                [
                    'attribute' => 'service_group_name',
                ],
                [
                    'attribute' => 'service_group_status',
                ],
                [
                    'attribute' => 'service_id',
                ],
                [
                    'attribute' => 'service_name',
                ],
                [
                    'attribute' => 'service_group_id',
                ],
                [
                    'attribute' => 'print_template_id',
                ],
                [
                    'attribute' => 'print_copy_qty',
                ],
                [
                    'attribute' => 'service_prefix',
                ],
                [
                    'attribute' => 'service_numdigit',
                ],
                [
                    'attribute' => 'service_status',
                    'value' => function ($model, $key, $index) {
                        return static::getBadgeStatus($model['service_status']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'service_status_id',
                    'value' => function ($model, $key, $index) {
                        return $model['service_status'];
                    },
                ],
                [
                    'attribute' => 'hos_name_th',
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'updateOptions' => [
                        'role' => 'modal-remote',
                        'style' => 'font-size: 2em;',
                        'title' => Yii::t('frontend','Edit'),
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => Yii::t('frontend','Delete'),
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'update') {
                            return Url::to(['/app/settings/update-service-group', 'id' => $key, 'service_id' => $model['service_id']]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/settings/delete-service-group', 'id' => $key, 'service_id' => $model['service_id']]);
                        }
                    },
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #ข้อมูลจุดบริการ
    public static function getDataCounterService()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_counter_service_type.counter_service_type_id',
                'tb_counter_service_type.counter_service_type_name',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service_group.service_group_name',
                'tb_counter_service.counter_service_id',
                'tb_counter_service.sound_station_id',
                'tb_counter_service.sound_number_id',
                'tb_counter_service.sound_service_id',
                'tb_counter_service.counter_service_order',
                'tb_counter_service.counter_service_status',
            ])
            ->from('tb_counter_service_type')
            ->leftJoin('tb_counter_service', 'tb_counter_service.counter_service_type_id = tb_counter_service_type.counter_service_type_id')
            ->leftJoin('tb_service_group', 'tb_service_group.service_group_id = tb_counter_service.service_group_id')
            ->orderBy('counter_service_type_id ASC')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'counter_service_type_id',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'counter_service_type_id',
                ],
                [
                    'attribute' => 'counter_service_type_name',
                ],
                [
                    'attribute' => 'counter_service_id',
                ],
                [
                    'attribute' => 'counter_service_name',
                ],
                [
                    'attribute' => 'counter_service_call_number',
                ],
                [
                    'attribute' => 'service_group_name',
                ],
                [
                    'attribute' => 'sound_station_id',
                ],
                [
                    'attribute' => 'sound_name1',
                    'value' => function ($model, $key, $index) {
                        return static::getSoundname($model['sound_service_id']);
                    },
                ],
                [
                    'attribute' => 'sound_name2',
                    'value' => function ($model, $key, $index) {
                        return static::getSoundname($model['sound_number_id']);
                    },
                ],
                [
                    'attribute' => 'counter_service_status',
                    'value' => function ($model, $key, $index) {
                        return static::getBadgeStatus($model['counter_service_status']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status_id',
                    'value' => function ($model, $key, $index) {
                        return $model['counter_service_status'];
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'updateOptions' => [
                        'role' => 'modal-remote',
                        'style' => 'font-size: 2em;',
                        'title' => 'แก้ไข',
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => 'ลบ',
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'update') {
                            return Url::to(['/app/settings/update-counter-service', 'id' => $key, 'counter_service_id' => $model['counter_service_id']]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/settings/delete-counter-service', 'counter_service_type_id' => $key, 'counter_service_id' => $model['counter_service_id']]);
                        }
                    },
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #ข้อมูลโปรแกรมเสียง
    public static function getDataSoundStation()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_sound_station.*'
            ])
            ->from('tb_sound_station')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'sound_station_id',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'sound_station_id',
                ],
                [
                    'attribute' => 'sound_station_name',
                ],
                [
                    'attribute' => 'counter_service_id',
                    'value' => function ($model, $key, $index) {
                        return static::getCounterServiceName($model['counter_service_id']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'sound_station_status',
                    'value' => function ($model, $key, $index) {
                        return static::getBadgeStatus($model['sound_station_status']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'sound_station_status_id',
                    'value' => function ($model, $key, $index) {
                        return $model['sound_station_status'];
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'updateOptions' => [
                        'role' => 'modal-remote',
                        'style' => 'font-size: 2em;',
                        'title' => 'แก้ไข',
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => 'ลบ',
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'update') {
                            return Url::to(['/app/settings/update-sound-station', 'id' => $key]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/settings/delete-sound-station', 'id' => $key]);
                        }
                    }
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #ข้อมูลเซอร์วิสโปรไฟล์
    public static function getDataServiceProfile()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_service_profile.*',
                'tb_counter_service_type.*'
            ])
            ->from('tb_service_profile')
            ->innerJoin('tb_counter_service_type', 'tb_counter_service_type.counter_service_type_id = tb_service_profile.counter_service_type_id')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'service_profile_id',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'service_profile_id',
                ],
                [
                    'attribute' => 'service_profile_name',
                ],
                [
                    'attribute' => 'counter_service_type_name',
                ],
                [
                    'attribute' => 'service_names',
                    'value' => function ($model, $key, $index) {
                        return static::getServiceNames($model['service_id']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'service_profile_status',
                    'value' => function ($model, $key, $index) {
                        return AppQuery::getBadgeStatus($model['service_profile_status']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'service_profile_status_id',
                    'value' => function ($model, $key, $index) {
                        return $model['service_profile_status'];
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'updateOptions' => [
                        'role' => 'modal-remote',
                        'style' => 'font-size: 2em;',
                        'title' => 'แก้ไข',
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => 'ลบ',
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'update') {
                            return Url::to(['/app/settings/update-service-profile', 'id' => $key]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/settings/delete-service-profile', 'id' => $key]);
                        }
                    }
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #ข้อมูลบัตรคิว
    public static function getDataTicket()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_ticket.*',
            ])
            ->from('tb_ticket')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'ticket_ids',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'ticket_ids',
                ],
                [
                    'attribute' => 'hos_name_th',
                ],
                [
                    'attribute' => 'hos_name_en',
                ],
                [
                    'attribute' => 'barcode_type',
                ],
                [
                    'attribute' => 'ticket_status',
                    'value' => function ($model, $key, $index) {
                        return static::getBadgeStatus($model['ticket_status']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'ticket_status_id',
                    'value' => function ($model, $key, $index) {
                        return $model['ticket_status'];
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'updateOptions' => [
                        'style' => 'font-size: 2em;',
                        'title' => 'แก้ไข',
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => 'ลบ',
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'update') {
                            return Url::to(['/app/settings/update-ticket', 'id' => $key]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/settings/delete-ticket', 'id' => $key]);
                        }
                    },
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #ข้อมูลจอแสดงผล
    public static function getDataDisplay()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_display.*'
            ])
            ->from('tb_display')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'display_ids',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'display_ids',
                ],
                [
                    'attribute' => 'display_name',
                ],
                [
                    'attribute' => 'display_status',
                    'value' => function ($model, $key, $index) {
                        return static::getBadgeStatus($model['display_status']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'display_status_id',
                    'value' => function ($model, $key, $index) {
                        return $model['display_status'];
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{duplicate} {update} {delete}',
                    'updateOptions' => [
                        'style' => 'font-size: 2em;',
                        'title' => 'แก้ไข',
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => 'ลบ',
                    ],
                    'buttons' => [
                        'duplicate' => function ($url, $model, $key) {
                            return Html::a(Icon::show('copy'), ['/app/settings/copy-display', 'id' => $key], ['class' => 'activity-copy', 'title' => 'Duplicate', 'style' => 'font-size: 2em;',]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'update') {
                            return Url::to(['/app/settings/update-display', 'id' => $key]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/settings/delete-display', 'id' => $key]);
                        }
                    }
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #รายการคิว
    public static function getDataQueList()
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_que.que_ids',
                'tb_que.que_num',
                'tb_que.que_hn',
                'tb_que.pt_name',
                'tb_que.service_id',
                'tb_que.service_group_id',
                'tb_que.que_status_id',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_que_status.que_status_name',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_caller.caller_ids',
                'tb_counter_service.counter_service_name'
            ])
            ->from('tb_que')
            ->innerJoin('tb_que_status', 'tb_que_status.que_status_id = tb_que.que_status_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->leftJoin('tb_caller', 'tb_caller.que_ids = tb_que.que_ids')
            ->leftJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->groupBy('tb_que.que_ids')
            ->orderBy('tb_que.que_ids DESC')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'que_ids',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'que_ids',
                ],
                [
                    'attribute' => 'que_num',
                ],
                [
                    'attribute' => 'que_num_badge',
                    'value' => function ($model, $key, $index, $column) {
                        return \kartik\helpers\Html::badge($model['que_num'], ['class' => 'badge badge-success', 'style' => 'font-size: 20px;font-weight: 600;width: 80px;']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'que_hn',
                ],
                [
                    'attribute' => 'pt_name',
                ],
                [
                    'attribute' => 'service_id',
                ],
                [
                    'attribute' => 'service_group_id',
                ],
                [
                    'attribute' => 'que_status_id',
                ],
                [
                    'attribute' => 'counter_service_name',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model, $key, $index) {
                        return Html::tag('code', Yii::$app->formatter->asDate($model['created_at'], 'php:H:i:s'));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_time',
                ],
                [
                    'attribute' => 'service_name',
                ],
                [
                    'attribute' => 'service_group_name',
                ],
                [
                    'attribute' => 'que_status_name',
                    'value' => function ($model, $key, $index, $column) {
                        $badgeClass = 'badge';
                        switch ($model['que_status_id']) {
                            case 1: //รอเรียก
                                $badgeClass = 'badge badge-warning';
                                break;
                            case 2: //กำลังเรียก
                                $badgeClass = 'badge';
                                break;
                            case 3: //พักคิว
                                $badgeClass = 'badge badge-danger';
                                break;
                            case 4: //เสร็จสิ้น
                                $badgeClass = 'badge badge-success';
                                break;
                            default:
                                $badgeClass = 'badge';
                        }
                        return \kartik\helpers\Html::badge($model['que_status_name'].' '.$model['counter_service_name'], ['class' => $badgeClass, 'style' => 'width: 160px;']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status_name',
                    'value' => function ($model, $key, $index, $column) {
                        return $model['que_status_name'];
                    }
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{qrcode} {print} {update} {delete}',
                    'updateOptions' => [
                        'role' => 'modal-remote',
                        'style' => 'font-size: 2em;',
                        'title' => 'แก้ไข',
                    ],
                    'deleteOptions' => [
                        'class' => 'text-danger activity-delete',
                        'style' => 'font-size: 2em;',
                        'title' => 'ลบ',
                    ],
                    'buttons' => [
                        'qrcode' => function ($url, $model, $key) {
                            return Html::a(Icon::show('qrcode', ['style' => 'font-size: 2em;'], Icon::BSG), $url, ['target' => '_blank', 'title' => 'QRCode',]);
                        },
                        'print' => function ($url, $model, $key) {
                            return Html::a(Icon::show('print', ['style' => 'font-size: 2em;'], Icon::BSG), $url, ['target' => '_blank', 'title' => 'พิมพ์บัตรคิว',]);
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'qrcode') {
                            return Url::to(['/qrcode/mobile-view', 'id' => $key]);
                        }
                        if ($action == 'print') {
                            return Url::to(['/app/kiosk/print-ticket', 'que_ids' => $key]);
                        }
                        if ($action == 'update') {
                            return Url::to(['/app/kiosk/update-que', 'id' => $key]);
                        }
                        if ($action == 'delete') {
                            return Url::to(['/app/kiosk/delete-que', 'id' => $key]);
                        }
                    },
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #ข้อมูลคิวรอเรียก
    public static function getDataQueWait($params)
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_que.que_ids',
                'tb_que.que_num',
                'tb_que.que_hn',
                'tb_que.pt_name',
                'tb_que.service_id',
                'tb_que.service_group_id',
                'tb_que.created_at',
                'tb_que.que_status_id',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_que_status.que_status_name',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_service.service_prefix'
            ])
            ->from('tb_que')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->where(['tb_que.que_status_id' => [1,5], 'tb_que.service_id' => $params['service_id']])
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'que_ids',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'que_ids',
                ],
                [
                    'attribute' => 'que_num',
                ],
                [
                    'attribute' => 'que_num_badge',
                    'value' => function ($model, $key, $index, $column) {
                        return \kartik\helpers\Html::badge($model['que_num'], ['class' => 'badge badge-success', 'style' => 'font-size: 20px;font-weight: 600;width: 80px;']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'que_hn',
                ],
                [
                    'attribute' => 'pt_name',
                ],
                [
                    'attribute' => 'service_id',
                ],
                [
                    'attribute' => 'service_group_id',
                ],
                [
                    'attribute' => 'created_at',
                ],
                [
                    'attribute' => 'que_status_id',
                ],
                [
                    'attribute' => 'service_prefix',
                ],
                [
                    'attribute' => 'created_time',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index) {
                        return Html::tag('code', $model['created_time']);
                    },
                ],
                [
                    'attribute' => 'que_status_name',
                ],
                [
                    'attribute' => 'service_name',
                ],
                [
                    'attribute' => 'service_group_name',
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{call} {end}',
                    'buttons' => [
                        'call' => function ($url, $model, $key) {
                            return Html::a(Icon::show('hand-pointer-o') . Yii::t('frontend', 'CALL'), false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-info btn-outline on-calling',
                                'data-url' => $url,
                            ]);
                        },
                        'end' => function ($url, $model, $key) {
                            return Html::a(Icon::show('check') . Yii::t('frontend', 'END'), false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-success btn-outline on-end',
                                'data-url' => $url,
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'call') {
                            return Url::to(['/app/calling/call-waiting', 'que_ids' => $key]);
                        }
                        if ($action == 'end') {
                            return Url::to(['/app/calling/end-waiting', 'que_ids' => $key]);
                        }
                    }
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    // คิวกำลังเรียก
    public static function getDataQueCalling($data, $formData)
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name',
                'tb_service.service_prefix'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => [1, 3],
                'tb_caller.counter_service_id' => $formData['counter_service_id'],
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_status_id' => 2
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'caller_ids',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'caller_ids',
                ],
                [
                    'attribute' => 'que_ids',
                ],
                [
                    'attribute' => 'service_profile_id',
                ],
                [
                    'attribute' => 'counter_service_id',
                ],
                [
                    'attribute' => 'call_timestp',
                    'value' => function ($model, $key, $index, $column) {
                        return Html::tag('code', Yii::$app->formatter->asDate($model['call_timestp'], 'php:H:i:s'));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'que_num_badge',
                    'value' => function ($model, $key, $index, $column) {
                        return \kartik\helpers\Html::badge($model['que_num'], ['class' => 'badge badge-success', 'style' => 'width: 80px;font-size: 20px;font-weight: 600;']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'call_status_id',
                ],
                [
                    'attribute' => 'caller_status_name',
                ],
                [
                    'attribute' => 'counter_service_name',
                ],
                [
                    'attribute' => 'counter_service_call_number',
                ],
                [
                    'attribute' => 'que_num',
                ],
                [
                    'attribute' => 'pt_name',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model, $key, $index, $column) {
                        return Html::tag('code', Yii::$app->formatter->asDate($model['created_at'], 'php:H:i:s'));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_time',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index) {
                        return Html::tag('code', $model['created_time']);
                    },
                ],
                [
                    'attribute' => 'service_name',
                ],
                [
                    'attribute' => 'service_group_name',
                ],
                [
                    'attribute' => 'service_id',
                ],
                [
                    'attribute' => 'service_group_id',
                ],
                [
                    'attribute' => 'service_prefix',
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{recall} {hold} {end}',
                    'dropdown' => false,
                    'dropdownButton' => [
                        'class' => 'btn btn-primary btn-outline',
                        'label' => 'ดำเนินการ'
                    ],
                    'dropdownOptions' => [
                        'class' => 'dropdown dropdown-action'
                    ],
                    'buttons' => [
                        'recall' => function ($url, $model, $key) {
                            return Html::a(Icon::show('hand-pointer-o') . Yii::t('frontend', 'CALL'), false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-info btn-outline on-recall',
                                'data-url' => $url,
                            ]);
                        },
                        'hold' => function ($url, $model, $key) {
                            return Html::a(Icon::show('hand-stop-o') . Yii::t('frontend', 'HOLD'), false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-warning btn-outline on-hold',
                                'data-url' => $url,
                            ]);
                        },
                        'end' => function ($url, $model, $key) {
                            return Html::a(Icon::show('check') . Yii::t('frontend', 'END'), false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-success btn-outline on-end',
                                'data-url' => $url,
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'recall') {
                            return Url::to(['/app/calling/recall', 'caller_ids' => $key]);
                        }
                        if ($action == 'hold') {
                            return Url::to(['/app/calling/hold', 'caller_ids' => $key]);
                        }
                        if ($action == 'end') {
                            return Url::to(['/app/calling/end', 'caller_ids' => $key]);
                        }
                    }
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    //พักคิว
    public static function getDataQueHold($data, $formData)
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name',
                'tb_service.service_prefix'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => 2,
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_status_id' => 3
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => false,
            ],
            'key' => 'caller_ids',
        ]);
        $columns = Yii::createObject([
            'class' => DataColumn::className(),
            'dataProvider' => $dataProvider,
            'formatter' => Yii::$app->formatter,
            'columns' => [
                [
                    'attribute' => 'caller_ids',
                ],
                [
                    'attribute' => 'que_ids',
                ],
                [
                    'attribute' => 'service_profile_id',
                ],
                [
                    'attribute' => 'counter_service_id',
                ],
                [
                    'attribute' => 'call_timestp',
                ],
                [
                    'attribute' => 'que_num_badge',
                    'value' => function ($model, $key, $index, $column) {
                        return \kartik\helpers\Html::badge($model['que_num'], ['class' => 'badge badge-success', 'style' => 'width: 80px;font-size: 20px;font-weight: 600;']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'call_status_id',
                ],
                [
                    'attribute' => 'caller_status_name',
                ],
                [
                    'attribute' => 'counter_service_name',
                ],
                [
                    'attribute' => 'counter_service_call_number',
                ],
                [
                    'attribute' => 'que_num',
                ],
                [
                    'attribute' => 'pt_name',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model, $key, $index, $column) {
                        return Html::tag('code', Yii::$app->formatter->asDate($model['created_at'], 'php:H:i:s'));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_time',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index) {
                        return Html::tag('code', $model['created_time']);
                    },
                ],
                [
                    'attribute' => 'service_name',
                ],
                [
                    'attribute' => 'service_group_name',
                ],
                [
                    'attribute' => 'service_id',
                ],
                [
                    'attribute' => 'service_group_id',
                ],
                [
                    'attribute' => 'service_prefix',
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{recall} {end}',
                    'dropdown' => false,
                    'dropdownButton' => [
                        'class' => 'btn btn-primary btn-outline',
                        'label' => 'ดำเนินการ'
                    ],
                    'dropdownOptions' => [
                        'class' => 'dropdown dropdown-action'
                    ],
                    'buttons' => [
                        'recall' => function ($url, $model, $key) {
                            return Html::a(Icon::show('hand-pointer-o') . ' เรียกคิว', false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-info btn-outline on-recall',
                                'data-url' => $url,
                            ]);
                        },
                        'end' => function ($url, $model, $key) {
                            return Html::a(Icon::show('hand-pointer-o') . ' เสร็จสิ้น', false, [
                                //'style' => 'font-size: 1.5em;',
                                'class' => 'btn btn-success btn-outline on-end',
                                'data-url' => $url,
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'recall') {
                            return Url::to(['/app/calling/recall', 'caller_ids' => $key]);
                        }
                        if ($action == 'end') {
                            return Url::to(['/app/calling/end', 'caller_ids' => $key]);
                        }
                    }
                ],
            ],
        ]);
        return $columns->renderDataColumns();
    }

    #คิวที่กำลังเรียกล่าสุด
    public static function getDataQueCurrentCall($service_ids, $counter_service_ids)
    {
        $rows = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller.call_status_id',
                'tb_que.que_num',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service.service_prefix',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->where([
                'tb_caller.call_status_id' => [1],
                'tb_que.service_id' => $service_ids,
                'tb_counter_service.counter_service_type_id' => $counter_service_ids,
                'tb_que.que_status_id' => 2
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->one();
        return $rows;
    }

    #รายการคิวที่แสดงผลบนหน้าจอ display
    public static function getDataQueDisplay($service_ids,$counter_service_ids,$lastCall, $config)
    {
        $rows = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller.call_status_id',
                'tb_que.que_num',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service.service_prefix',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->where([
                'tb_caller.call_status_id' => [1, 3],
                'tb_que.service_id' => $service_ids,
                'tb_counter_service.counter_service_type_id' => $counter_service_ids,
                'tb_que.que_status_id' => 2
            ])
            ->limit($config['page_length'])
            ->orderBy(['tb_caller.call_timestp' => SORT_DESC]);

        if ($lastCall) {
            $rows->andWhere('tb_caller.call_timestp <= :call_timestp', [':call_timestp' => $lastCall['call_timestp']]);
        }

        return $rows->all();
    }

    public static function getDataQueDisplayByCounterNumber($service_ids,$counter_service_ids,$lastCall,$counter_call_number,$config)
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller.call_status_id',
                'tb_que.que_num',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service.service_prefix',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->where([
                'tb_caller.call_status_id' => [1, 3],
                'tb_que.service_id' => $service_ids,
                'tb_counter_service.counter_service_type_id' => $counter_service_ids,
                'tb_counter_service.counter_service_call_number' => $counter_call_number,
                'tb_que.que_status_id' => 2
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_DESC])
            ->limit($config['que_column_length']);

        if ($lastCall) {
            $query->andWhere('tb_caller.call_timestp <= :call_timestp', [':call_timestp' => $lastCall['call_timestp']]);
        }

        return $query->all();
    }

    public static function getDataDisplayByPrefix($service_ids,$counter_service_ids,$lastCall,$prefix)
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller.call_status_id',
                'tb_que.que_num',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service.service_prefix',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->where([
                'tb_caller.call_status_id' => [1, 3],
                'tb_que.service_id' => $service_ids,
                'tb_counter_service.counter_service_type_id' => $counter_service_ids,
                'tb_service.service_prefix' => $prefix,
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_DESC]);

        if ($lastCall) {
            $query->andWhere('tb_caller.call_timestp <= :call_timestp', [':call_timestp' => $lastCall['call_timestp']]);
        }

        return $query->one();
    }

    // รายการพักคิว display
    public static function getDataQueHoldDisplay($service_ids, $counter_service_ids)
    {
        $rows = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller.call_status_id',
                'tb_que.que_num',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service.service_prefix',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->where([
                'tb_caller.call_status_id' => [2],
                'tb_que.service_id' => $service_ids,
                'tb_counter_service.counter_service_type_id' => $counter_service_ids,
                'tb_que.que_status_id' => 3
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_DESC])
            ->all();
        return $rows;
    }

    public static function getBadgeStatus($status)
    {
        if ($status == 0) {
            return \kartik\helpers\Html::badge(Icon::show('close') .Yii::t('frontend','UnActive'), ['class' => 'badge badge-danger']);
        } elseif ($status == 1) {
            return \kartik\helpers\Html::badge(Icon::show('check') .Yii::t('frontend','Active'), ['class' => 'badge badge-success']);
        }
    }

    public static function getSoundname($id)
    {
        $model = TbSound::findOne($id);
        if ($model) {
            return $model['sound_th'];
        } else {
            return '-';
        }
    }

    public static function getCounterServiceName($json)
    {
        $li = [];
        if (!empty($json)) {
            $arr = CoreUtility::string2Array($json);
            $rows = (new \yii\db\Query())
                ->select([
                    'tb_counter_service.counter_service_name',
                    'tb_counter_service_type.counter_service_type_name'
                ])
                ->from('tb_counter_service')
                ->innerJoin('tb_counter_service_type','tb_counter_service.counter_service_type_id = tb_counter_service_type.counter_service_type_id')
                ->where(['tb_counter_service.counter_service_id' => $arr])
                ->all();
            foreach ($rows as $key => $value) {
                $li[] = Html::tag('li', $value['counter_service_type_name'].' => '.$value['counter_service_name']);
            }
        }
        return count($li) > 0 ? Html::tag('ul', implode("\n", $li)) : '';
    }

    public static function getServiceNames($json)
    {
        $li = [];
        if (!empty($json)) {
            $arr = CoreUtility::string2Array($json);
            $model = TbService::find()->where(['service_id' => $arr])->all();
            foreach ($model as $key => $value) {
                $li[] = Html::tag('li', $value['service_name']);
            }
        }
        return count($li) > 0 ? Html::tag('ul', implode("\n", $li)) : '';
    }
}