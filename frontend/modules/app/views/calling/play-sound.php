<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 17/11/2561
 * Time: 15:00
 */
use homer\assets\jPlayerAsset;
use frontend\assets\SocketIOAsset;
use homer\sweetalert2\assets\SweetAlert2Asset;
use kartik\form\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use frontend\modules\app\models\TbSoundStation;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

SweetAlert2Asset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);

$this->title = Yii::t('frontend', 'Player');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile("@web/css/jPlayer.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
$this->registerJs('var model = '. Json::encode($model).';',View::POS_HEAD);
?>
    <div class="hpanel">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
            <?= $this->title ?>
        </div>
        <div class="panel-body">
            <?php  $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'id' => 'form-sound-station']); ?>
            <div class="form-group">
                <div class="col-sm-4">
                    <?= $form->field($model, 'sound_station_id', ['showLabels'=>false])->widget(Select2::classname(), [
                        'data'=> ArrayHelper::map(TbSoundStation::find()->where(['sound_station_status' => 1])->asArray()->all(),'sound_station_id','sound_station_name') ,
                        'pluginOptions'=>['allowClear'=>true],
                        'options' => ['placeholder'=>'Select state...'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginEvents' => [
                            "change" => "function(e) {
                            $('#form-sound-station').yiiActiveForm('validate', true);
                        }",
                        ],
                    ]); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <section>
                <div id="jplayer" class="jp-jplayer"></div>

                <div id="jp_container">
                    <div class="jp-gui ui-widget ui-widget-content ui-corner-all">
                        <ul>
                            <li class="jp-play ui-state-default ui-corner-all"><a href="javascript:;" class="jp-play ui-icon ui-icon-play" tabindex="1" title="play">play</a></li>
                            <li class="jp-pause ui-state-default ui-corner-all"><a href="javascript:;" class="jp-pause ui-icon ui-icon-pause" tabindex="1" title="pause">pause</a></li>
                            <li class="jp-stop ui-state-default ui-corner-all"><a href="javascript:;" class="jp-stop ui-icon ui-icon-stop" tabindex="1" title="stop">stop</a></li>
                            <li class="jp-repeat ui-state-default ui-corner-all"><a href="javascript:;" class="jp-repeat ui-icon ui-icon-refresh" tabindex="1" title="repeat">repeat</a></li>
                            <li class="jp-repeat-off ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-repeat-off ui-icon ui-icon-refresh" tabindex="1" title="repeat off">repeat off</a></li>
                            <li class="jp-mute ui-state-default ui-corner-all"><a href="javascript:;" class="jp-mute ui-icon ui-icon-volume-off" tabindex="1" title="mute">mute</a></li>
                            <li class="jp-unmute ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-unmute ui-icon ui-icon-volume-off" tabindex="1" title="unmute">unmute</a></li>
                            <li class="jp-volume-max ui-state-default ui-corner-all"><a href="javascript:;" class="jp-volume-max ui-icon ui-icon-volume-on" tabindex="1" title="max volume">max volume</a></li>
                        </ul>
                        <div class="jp-progress-slider"></div>
                        <div class="jp-volume-slider"></div>
                        <div class="jp-current-time"></div>
                        <div class="jp-title"></div>
                        <div class="jp-duration"></div>
                        <div class="jp-clearboth"></div>
                    </div>
                </div>
                <div id="jplayer_inspector"></div>
            </section>
        </div>
    </div>

<?php
$this->registerJsFile(
    '@web/js/jPlayer.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJs(<<<JS
    Que.autoloadMedia(model);
JS
);