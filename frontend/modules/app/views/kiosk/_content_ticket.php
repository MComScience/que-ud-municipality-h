<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 22:06
 */

use yii\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\app\models\TbServiceGroup;
use kartik\select2\Select2;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use frontend\assets\SocketIOAsset;

SocketIOAsset::register($this);
$this->registerJs('var com_name = ' . Json::encode($provider->getHostname()) . '; ', View::POS_HEAD);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var modelServiceGroup = ' . Json::encode($modelServiceGroup) . '; ', View::POS_HEAD);
$this->registerJs('var select2Options = ' . Json::encode(ArrayHelper::map($services, 'service_id', 'service_name')) . '; ', View::POS_HEAD);
?>
<div class="panel-body">
    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, 'id' => 'form-scan']); ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <h2>
                <?= Icon::show('address-card-o') ?>
                <?= empty($modelServiceGroup['service_group_name']) ? $this->title : $modelServiceGroup['service_group_name']; ?>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <div class="col-md-9">
                    <?= $form->field($model, 'service_group_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(TbServiceGroup::find()->asArray()->all(), 'service_group_id', 'service_group_name'),
                        'language' => 'th',
                        'options' => ['placeholder' => 'กลุ่มบริการ'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginEvents' => [
                            "change" => "function() {
                            var service_group_id = $(this).val();
                            if(service_group_id !== '' && service_group_id !== null){
                                location.replace(baseUrl + '/app/kiosk/index?service_group_id=' +  service_group_id);
                            }else{
                                location.replace(baseUrl + '/app/kiosk/index');
                            }
                        }",
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <div class="col-md-9">
                    <?= $form->field($model, 'card_id')->textInput([
                        'readonly' => true,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-11">
            <?php foreach ($services as $service) : ?>
                <span class="badge badge-success">
                    <?= Icon::show('check-square-o') ?>
                    <?= $service['service_name']; ?>
                    (<?= $service['service_prefix']; ?>)
                </span>&nbsp;
            <?php endforeach; ?>
        </div>
    </div>
    <div class="m">
        <div class="progress m-t-xs full progress-striped active" style="display: none;">
            <div style="width: 100%;text-align: center;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class=" progress-bar progress-bar-warning">
                กำลังอ่านบัตร...
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <strong>ขั้นตอนการออกบัตรคิว!</strong>
                <ol>
                    <li>เลือกกลุ่มบริการ</li>
                    <li>สแกนบัตรประชาชน</li>
                    <li>เลือกชื่อบริการ</li>
                    <li>ยืนยันคำสั่ง</li>
                </ol>
                <strong>Notice!</strong>
                <ul>
                    <li>การสแกนบัตรประชาชนครั้งแรก ให้เสียบบัตรประชาชนที่เครื่องอ่านบัตรก่อนทุกครั้งก่อนเปิดโปรแกรมอ่านบัตร</li>
                </ul>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJsFile(
    '@web/js/kiosk.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()],
        'position' => View::POS_END
    ]
);
?>
