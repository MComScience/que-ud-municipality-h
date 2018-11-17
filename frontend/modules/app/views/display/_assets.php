<?php

use frontend\assets\ModernBlinkAsset;
use frontend\assets\SocketIOAsset;
use homer\assets\FontAwesomeAsset;
use homer\sweetalert2\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

SweetAlert2Asset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);
FontAwesomeAsset::register($this);
ModernBlinkAsset::register($this);

$this->registerCssFile("@web/css/display2.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);

$this->registerCss($config['display_css']);
$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
$this->registerJs('var config = '. Json::encode($config).';',View::POS_HEAD);
$this->registerJs('var services = '. Json::encode($service_ids).';',View::POS_HEAD);
$this->registerJs('var counters = '. Json::encode($counters).';',View::POS_HEAD);
?>