<?php
/* @var $this yii\web\View */

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use kartik\icons\Icon;
use yii\helpers\Html;
use frontend\assets\SocketIOAsset;

SocketIOAsset::register($this);

$this->title = Yii::t('frontend', 'Calling');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var modelProfile = ' . Json::encode($modelProfile) . ';', View::POS_HEAD);
$this->registerJs('var formData = ' . Json::encode($formData) . ';', View::POS_HEAD);

$this->registerCssFile("@web/css/mobile-menu.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
$this->registerCssFile("@web/css/calling.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
?>
<div class="hpanel">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-1"> <?= $this->title ?></a></li>
        <li class="">
            <a data-toggle="tab" href="#tab-2">
                <?= Yii::t('frontend', 'Queue List') ?> <?= Html::tag('span', '0', ['class' => 'label label-success', 'id' => 'count-qdata']) ?>
            </a>
        </li>
    </ul>
    <div class="tab-content ">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                <?php echo $this->render('_form',[
                    'modelProfile' => $modelProfile,
                    'services' => $services,
                    'formData' => $formData
                ]); ?>
                <div class="tab-content">
                    <div id="tab-wait" class="tab-pane active">
                        <?php echo $this->render('_content_tab_wait',[
                            'modelProfile' => $modelProfile,
                            'services' => $services,
                            'formData' => $formData
                        ]); ?>
                    </div>
                    <div id="tab-call" class="tab-pane">
                        <?php echo $this->render('_content_tab_call',[
                            'modelProfile' => $modelProfile,
                            'services' => $services,
                            'formData' => $formData
                        ]); ?>
                    </div>
                    <div id="tab-hold" class="tab-pane">
                        <?php echo $this->render('_content_tab_hold',[
                            'modelProfile' => $modelProfile,
                            'services' => $services,
                            'formData' => $formData
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-2" class="tab-pane">
            <div class="panel-body">
                <?php echo $this->render('@frontend/modules/app/views/kiosk/_content_que_list'); ?>
            </div>
        </div>
    </div>
</div>

<div class="mobile-menu-bs bootstrap">
    <ul class="">
        <li class="active" data-id="tab-wait" style="width: 33.33%">
            <a data-toggle="tab" href="#tab-wait">
                <div class="icon"><?= Icon::show('list') ?></div>
                <div class="h1">
                    <?= Yii::t('frontend', 'WAIT') ?> <span class="badge badge-warning count-wait">0</span>
                </div>
            </a>
        </li>
        <li data-id="tab-call" style="width: 33.33%">
            <a data-toggle="tab" href="#tab-call">
                <div class="icon"><?= Icon::show('list') ?></div>
                <div class="h1">
                    <?= Yii::t('frontend', 'CALLING') ?> <span class="badge badge-warning count-call">0</span>
                </div>
            </a>
        </li>
        <li data-id="tab-hold" style="width: 33.33%">
            <a data-toggle="tab" href="#tab-hold">
                <div class="icon"><?= Icon::show('list') ?></div>
                <div class="h1">
                    <?= Yii::t('frontend', 'HOLD') ?> <span class="badge badge-warning count-hold">0</span>
                </div>
            </a>
        </li>
    </ul>
</div>
<?php
$this->registerJsFile(
    '@web/js/calling.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJs(<<<JS
    // hidden actions รายการคิว
    dt_tbquelist.column( 9 ).visible( false );
    dt_tbwaiting.on( 'order.dt search.dt', function () {
        dt_tbwaiting.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    var input = $('input[type="search"]');
    input.focus(function(){
        //animate
        $(this).animate({
            width: '250px',
        }, 400 );
        $(this).css("background-color", "#434a54");
        $(this).css("color", "#fff");
        $(this).removeClass("input-sm").addClass("input-lg");
    });
    
    input.blur(function(){
        $(this).animate({
            width: '160px'
        }, 500 );
        setTimeout(function(){
            input.css("background-color", "#fff");
            input.css("color", "black");
            input.removeClass("input-lg").addClass("input-sm");
        },500);
    });
JS
);
?>

