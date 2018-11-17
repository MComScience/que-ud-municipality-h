<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 16:34
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use homer\widgets\dynamicform\DynamicFormWidget;
use kartik\icons\Icon;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use frontend\modules\app\models\TbSoundStation;

$this->registerCss(<<<CSS
    .modal-dialog{
        width: 90%;
    }
    .select2-dropdown {
        z-index: 2100;
    }
    .form-horizontal .radio,
    .form-horizontal .checkbox,
    .form-horizontal .radio-inline,
    .form-horizontal .checkbox-inline {
        display: inline-block;
    }

    .swal2-container {
        z-index: 2200;
    }
    .form-group {
        margin-bottom: 0px;
    }
CSS
);
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-counter',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['showLabels' => false],
]);?>
    <div class="form-group">
        <?= Html::activeLabel($model, 'counter_service_type_name', ['label' => 'ชื่อประเภทบริการ','class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-8">
            <?= $form->field($model, 'counter_service_type_name',['showLabels'=>false])->textInput([
                'placeholder' => 'ชื่อประเภท'
            ]); ?>
        </div>
    </div>
<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => \Yii::$app->keyStorage->get('dynamic-limit', 20), // the maximum times, an element can be cloned (default 999)
    'min' => 0, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => $modelCounterServices[0],
    'formId' => 'form-counter',
    'formFields' => [
        'counter_service_id',
        'counter_service_name',
        'counter_service_call_number',
        'counter_service_type_id',
        'service_group_id',
        'sound_station_id',
        'sound_id',
        'sound_service_id',
        'counter_service_order',
        'counter_service_status'
    ],
    'clientEvents' => [
        'afterInsert' => 'function(e, item) {
                jQuery(".dynamicform_wrapper .panel-title").each(function(index) {
                    jQuery(this).html("รายการที่ : " + (index + 1));
                });
            }',
        'afterDelete' => 'function(e, item) {
                jQuery(".dynamicform_wrapper .panel-title").each(function(index) {
                    jQuery(this).html("รายการที่ : " + (index + 1));
                });
            }'
    ],
]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Icon::show('edit').'ช่องบริการย่อย'; ?>
            <?= Html::button(Icon::show('plus').'เพิ่มรายการ',['class' => 'pull-right add-item btn btn-success btn-xs']); ?>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body container-items"><!-- widgetContainer -->
            <?php foreach ($modelCounterServices as $index => $modelCounterService): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <?= Html::tag('span','รายการที่ : '.($index + 1),['class' => 'panel-title']); ?>
                        <div style="float: right;">
                            <?= Html::button(Icon::show('minus'),['class' => 'remove-item btn btn-danger btn-xs']); ?>
                            <?= Html::button(Icon::show('plus'),['class' => 'add-item btn btn-success btn-xs']); ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (! $modelCounterService->isNewRecord) {
                            echo Html::activeHiddenInput($modelCounterService, "[{$index}]counter_service_id");
                        }
                        ?>
                        <div class="form-group">
                            <?= Html::activeLabel($modelCounterService, "[{$index}]counter_service_name", ['label' => 'ชื่อช่องบริการ','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]counter_service_name",['showLabels'=>false])->textInput([
                                    'placeholder' => 'ชื่อช่องบริการ'
                                ]); ?>
                            </div>

                            <?= Html::activeLabel($modelCounterService, "[{$index}]counter_service_call_number", ['label' => 'หมายเลข','class'=>'col-sm-1 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]counter_service_call_number",['showLabels'=>false])->textInput([
                                    'placeholder' => 'หมายเลข',
                                ]); ?>
                            </div>
                        </div><!-- End FormGroup /-->

                        <div class="form-group">
                            <?= Html::activeLabel($modelCounterService, "[{$index}]service_group_id", ['label' => 'กลุ่มบริการ','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]service_group_id",['showLabels'=>false])->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map((new \yii\db\Query())
                                        ->select(['tb_service_group.service_group_id', 'tb_service_group.service_group_name'])
                                        ->from('tb_service_group')
                                        ->all(),'service_group_id','service_group_name'),
                                    'options' => ['placeholder' => 'เลือกกลุ่มบริการ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]) ?>
                            </div>

                            <?= Html::activeLabel($modelCounterService, "[{$index}]sound_station_id", ['label' => 'โปรแกรมเสียงเรียก','class'=>'col-sm-1 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]sound_station_id",['showLabels'=>false])->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(TbSoundStation::find()->where(['sound_station_status' => 1])->all(),'sound_station_id','sound_station_name'),
                                    'options' => ['placeholder' => 'เลือก...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]) ?>
                            </div>
                        </div><!-- End FormGroup /-->

                        <div class="form-group">
                            <?= Html::activeLabel($modelCounterService, "[{$index}]sound_service_id", ['label' => 'เสียงบริการ','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]sound_service_id",['showLabels'=>false])->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        (new \yii\db\Query())
                                            ->select(['CONCAT(tb_sound.sound_name,\' \',\'(\',tb_sound.sound_th,\')\') AS sound_name', 'tb_sound.sound_id'])
                                            ->from('tb_sound')
                                            ->where('sound_name LIKE :query')
                                            ->addParams([':query'=>'%Service%'])
                                            ->all(),'sound_id','sound_name'),
                                    'options' => ['placeholder' => 'เลือกไฟล์เสียง...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ])->hint('<small class="text-danger">Prompt1 = เสียงผู้หญิง , Prompt2 = เสียงผู้ชาย</small>') ?>
                            </div>

                            <?= Html::activeLabel($modelCounterService, "[{$index}]sound_number_id", ['label' => 'เสียงหมายเลข','class'=>'col-sm-1 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]sound_number_id",['showLabels'=>false])->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        (new \yii\db\Query())
                                            ->select(['CONCAT(tb_sound.sound_name,\' \',\'(\',tb_sound.sound_th,\')\') AS sound_name', 'tb_sound.sound_id'])
                                            ->from('tb_sound')
                                            ->where('sound_name NOT LIKE :query')
                                            ->addParams([':query'=>'%Service%'])
                                            ->all(),'sound_id','sound_name'),
                                    'options' => ['placeholder' => 'เลือกไฟล์เสียง...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ])->hint('<small class="text-danger">Prompt1 = เสียงผู้หญิง , Prompt2 = เสียงผู้ชาย</small>') ?>
                            </div>
                        </div><!-- End FormGroup /-->

                        <div class="form-group">
                            <?= Html::activeLabel($modelCounterService, "[{$index}]counter_service_status", ['class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($modelCounterService, "[{$index}]counter_service_status",['showLabels'=>false])->RadioList(
                                    [0 => Yii::t('frontend','UnActive'), 1 => Yii::t('frontend','Active')],[
                                    'inline'=>true,
                                    'item' => function($index, $label, $name, $checked, $value) {

                                        $return = '<div class="radio"><label style="font-size: 1em">';
                                        $return .= Html::radio( $name, $checked,['value' => $value]);
                                        $return .= '<span class="cr"><i class="cr-icon fa fa-circle"></i></span>' . ucwords($label);
                                        $return .= '</label></div>';

                                        return $return;
                                    }
                                ]); ?>
                            </div>
                        </div><!-- End FormGroup /-->

                    </div><!-- End Body Panel /-->
                </div><!-- End Panel /-->
            <?php endforeach; ?>
        </div><!-- End Body Panel /-->
    </div><!-- End Panel /-->
<?php DynamicFormWidget::end(); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: right;">
            <div class="form-group">
                <div class="col-sm-12">
                    <?= Html::button(Icon::show('close').Yii::t('frontend','Close'),['class' => 'btn btn-default','data-dismiss' => 'modal']); ?>
                    <?= Html::submitButton(Icon::show('save').Yii::t('frontend','Save'),['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
//Form Event
var table = $('#tb-counter-service').DataTable();
var \$form = $('#form-counter');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('#form-counter button[type="submit"]').button('loading');//loading btn
    \$.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            if(data.status == '200'){
                $('#ajaxCrudModal').modal('hide');//hide modal
                table.ajax.reload();//reload table
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function(){ \$btn.button('reset'); }, 1500);//clear button loading
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
            }
            \$btn.button('reset');
        },
        error: function(jqXHR, errMsg) {
            swal('Oops...',errMsg,'error');
            \$btn.button('reset');
        }
    });
    return false; // prevent default submit
});
JS
);
?>