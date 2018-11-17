<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/11/2561
 * Time: 21:01
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\icons\Icon;
use homer\widgets\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

$this->registerCss(<<<CSS
    .modal-dialog {
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
    'id' => 'form-service-group',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['showLabels' => false],
    'options' => ['autocomplete' => 'off']
]); ?>
<div class="form-group">
    <?= Html::activeLabel($model, 'service_group_name', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-8">
        <?= $form->field($model, 'service_group_name', ['showLabels' => false])->textInput([
            'placeholder' => 'ชื่อกลุ่มบริการ'
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
    'model' => $modelServices[0],
    'formId' => 'form-service-group',
    'formFields' => [
        'service_id',
        'service_name',
        'service_group_id',
        'print_template_id',
        'print_copy_qty',
        'service_prefix',
        'service_numdigit',
        'service_status',
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
        <?= Icon::show('edit') . 'กลุ่มบริการย่อย'; ?>
        <?= Html::button(Icon::show('plus') . Yii::t('frontend','Add'), ['class' => 'pull-right add-item btn btn-success btn-xs']); ?>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body container-items"><!-- widgetContainer -->
        <?php foreach ($modelServices as $index => $modelService): ?>
            <div class="item panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <?= Html::tag('span', 'รายการที่ : ' . ($index + 1), ['class' => 'panel-title']); ?>
                    <div style="float: right;">
                        <?= Html::button(Icon::show('minus'), ['class' => 'remove-item btn btn-danger btn-xs']); ?>
                        <?= Html::button(Icon::show('plus'), ['class' => 'add-item btn btn-success btn-xs']); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <?php
                    if (!$modelService->isNewRecord) {
                        echo Html::activeHiddenInput($modelService, "[{$index}]service_id");
                    }
                    ?>

                    <div class="form-group">
                        <?= Html::activeLabel($modelService, "[{$index}]service_name", ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-4">
                            <?= $form->field($modelService, "[{$index}]service_name", ['showLabels' => false])->textInput([
                                'placeholder' => 'ชื่อบริการ'
                            ]); ?>
                        </div>
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <?= Html::activeLabel($modelService, "[{$index}]print_template_id", ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-4">
                            <?= $form->field($modelService, "[{$index}]print_template_id", ['showLabels' => false])->widget(Select2::classname(), [
                                'data' => ArrayHelper::map((new \yii\db\Query())
                                    ->select(['tb_ticket.ticket_ids', 'tb_ticket.hos_name_th'])
                                    ->from('tb_ticket')
                                    ->all(), 'ticket_ids', 'hos_name_th'),
                                'options' => ['placeholder' => 'เลือกแบบการพิมพ์บัตรคิว...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'theme' => Select2::THEME_BOOTSTRAP,
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($modelService, "[{$index}]print_copy_qty", ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-4">
                            <?= $form->field($modelService, "[{$index}]print_copy_qty", ['showLabels' => false])->textInput([
                                'placeholder' => 'จำนวนพิมพ์/ครั้ง',
                            ]); ?>
                        </div>
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <?= Html::activeLabel($modelService, "[{$index}]service_prefix", ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-4">
                            <?= $form->field($modelService, "[{$index}]service_prefix", ['showLabels' => false])->textInput([
                                'placeholder' => 'ตัวอักษร/ตัวเลข นำหน้าคิว'
                            ]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($modelService, "[{$index}]service_numdigit", ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-4">
                            <?= $form->field($modelService, "[{$index}]service_numdigit", ['showLabels' => false])->textInput([
                                'placeholder' => 'จำนวนหลักหมายเลขคิว',
                            ]); ?>
                        </div>
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <?= Html::activeLabel($modelService, "[{$index}]service_status", ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-4">
                            <?= $form->field($modelService, "[{$index}]service_status", ['showLabels' => false])->RadioList(
                                [0 => Yii::t('frontend','UnActive'), 1 => Yii::t('frontend','Active')], [
                                'inline' => true,
                                'item' => function ($index, $label, $name, $checked, $value) {

                                    $return = '<div class="radio"><label style="font-size: 1em">';
                                    $return .= Html::radio($name, $checked, ['value' => $value]);
                                    $return .= '<span class="cr"><i class="cr-icon fa fa-circle"></i></span>' . ucwords($label);
                                    $return .= '</label></div>';

                                    return $return;
                                }
                            ]); ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div><!-- End panel body -->
</div><!-- end panel -->

<?php DynamicFormWidget::end(); ?>

<div class="form-group">
    <div class="col-sm-12 text-right">
        <?= Html::button(Icon::show('close') . Yii::t('frontend','Close'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
        <?= Html::submitButton(Icon::show('save') . Yii::t('frontend','Save'), ['class' => 'btn btn-primary']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
//Form Event
var table = $('#tb-service-group').DataTable();
var \$form = $('#form-service-group');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('#form-service-group button[type="submit"]').button('loading');//loading btn
    \$.ajax({
        url: \$form.attr('action'),
        type: \$form.attr('method'),
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
