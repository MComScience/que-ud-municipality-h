<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 16/11/2561
 * Time: 10:20
 */
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use frontend\modules\app\models\TbServiceProfile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use frontend\modules\app\models\TbCounterService;
?>
<div class="hpanel">
    <div class="panel-heading hbuilt">
        <div class="panel-tools">
            <a class="showhide-panel"><i class="fa fa-chevron-up"></i></a>
        </div>
        #<?= $this->title ?> <span id="hpanel-title"></span>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'id' => 'form-calling']); ?>
        <div class="form-group">
            <?= Html::activeLabel($modelProfile, 'service_profile_id', ['label' => Yii::t('frontend', 'Service Profile'),'class'=>'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($modelProfile, 'service_profile_id', ['showLabels'=>false])->widget(Select2::classname(), [
                    'data'=> ArrayHelper::map(TbServiceProfile::find()->where(['service_profile_status' => 1])->asArray()->all(),'service_profile_id','service_profile_name'),
                    'pluginOptions'=>['allowClear'=>true],
                    'options' => ['placeholder'=>'เซอร์วิสโปรไฟล์'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginEvents' => [
                        "change" => "function(e) {
                            if($(this).val() == '' || $(this).val() == null){
                                location.replace(baseUrl + \"/app/calling/index\");
                            }else{
                                location.replace(baseUrl + \"/app/calling/index?profile_id=\" +  $(this).val());
                            }
                        }",
                    ],
                ]); ?>
            </div>

            <?= Html::activeLabel($modelProfile, 'counter_service_id', ['label' => Yii::t('frontend', 'Counter Service'),'class'=>'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($modelProfile, 'counter_service_id', ['showLabels'=>false])->widget(DepDrop::classname(), [
                    'data' => ArrayHelper::map(TbCounterService::find()->where(['counter_service_type_id' => $modelProfile['counter_service_type_id'],'counter_service_status' => 1])->asArray()->all(),'counter_service_id','counter_service_name'),
                    'options'=>[
                        'placeholder'=>'เลือกจุดบริการ',
                    ],
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options'=>[
                        'pluginOptions'=>[
                            'allowClear'=>true
                        ],
                        'options' => ['placeholder'=>'เลือกจุดบริการ'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                                if($(this).val() != '' && $(this).val() != null){
                                    location.replace(baseUrl + \"/app/calling/index?profile_id=\" + $('#tbserviceprofile-service_profile_id').val() + \"&counter_service_id=\" + $(this).val());
                                }else{
                                    location.replace(baseUrl + \"/app/calling/index\");
                                }
                            }",
                        ],
                    ],
                    'pluginOptions'=>[
                        'depends'=>['tbserviceprofile-service_profile_id'],
                        'placeholder'=>'เลือกจุดบริการ',
                        'url' => Url::to(['/app/calling/child-profile'])
                    ],
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <?php foreach($services as $item): ?>
                    <?= \kartik\helpers\Html::badge($item['service_prefix'].': '.$item['service_name'],['class' => 'badge badge-primary']) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible" role="alert" style="margin-top: 10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">ปิด &times;</span></button>
                    <strong>Notice!</strong>
                    <ul>
                        <li>เลือกเซอร์วิสโปรไฟล์และจุดบริการก่อนทุกครั้ง</li>
                        <li>คลิกที่แท็บเมนูด้านล่างเพื่อสลับดูข้อมูล คิวรอเรียก, คิวกำลังเรียกและพักคิว</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
