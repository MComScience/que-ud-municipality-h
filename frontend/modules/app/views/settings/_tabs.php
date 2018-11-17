<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/11/2561
 * Time: 19:01
 */

use yii\bootstrap\Tabs;
use yii\helpers\Json;
use yii\helpers\Url;
use homer\widgets\Modal;
use yii\web\View;

$action = Yii::$app->controller->action->id;

$this->title = Yii::t('frontend', 'Settings').Yii::t('frontend', $title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Settings'), 'url' => Yii::$app->request->url];
$this->params['breadcrumbs'][] = Yii::t('frontend', $title);

$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);

echo Tabs::widget([
    'items' => [
        [
            'label' => 'กลุ่มบริการ',
            'content' => $this->render('_content_service_group'),
            'active' => $action == 'service-group' ? true : false,
            'url' => Url::to(['/app/settings/service-group']),
        ],
        [
            'label' => 'จุดบริการ',
            'content' => $this->render('_content_counter'),
            'active' => $action == 'counter' ? true : false,
            'url' => Url::to(['/app/settings/counter']),
        ],
        [
            'label' => 'โปรแกรมเสียงเรียก',
            'content' => $this->render('_content_sound_station'),
            'active' => $action == 'sound-station' ? true : false,
            'url' => Url::to(['/app/settings/sound-station']),
        ],
        [
            'label' => 'เซอร์วิสโปรไฟล์',
            'content' => $this->render('_content_service_profile'),
            'active' => $action == 'service-profile' ? true : false,
            'url' => Url::to(['/app/settings/service-profile']),
        ],
        [
            'label' => 'บัตรคิว',
            'content' => $this->render('_content_ticket'),
            'active' => $action == 'ticket' ? true : false,
            'url' => Url::to(['/app/settings/ticket']),
        ],
        [
            'label' => 'จอแสดงผล',
            'content' => $this->render('_content_display'),
            'active' => $action == 'display' ? true : false,
            'url' => Url::to(['/app/settings/display']),
        ],
        [
            'label' => 'รีเซ็ตคิว',
            'content' => $this->render('_content_reset'),
            'active' => $action == 'reset' ? true : false,
            'url' => Url::to(['/app/settings/reset']),
        ],
    ],
    'encodeLabels' => false,
]);

Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",
    'options' => ['class' => 'modal modal-danger', 'tabindex' => false,],
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

Modal::end();

$this->registerCssFile("@web/css/checkbox-bs.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);