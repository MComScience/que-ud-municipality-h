<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 19:51
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\icons\Icon;
use yii\helpers\ArrayHelper;

$this->registerCss(<<<CSS
    .modal-header{
        padding: 10px;
    }
    .select2-dropdown {
        z-index: 2100;
    }
    
    .swal2-container {
        z-index: 2200;
    }
    .form-group {
        margin-bottom: 0px;
    }
    .form-horizontal .field-tbsoundstation-sound_station_status .radio,
    .form-horizontal .field-tbsoundstation-sound_station_status .checkbox,
    .form-horizontal .field-tbsoundstation-sound_station_status .radio-inline,
    .form-horizontal .field-tbsoundstation-sound_station_status .checkbox-inline {
        display: inline-block;
    }
CSS
);
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-sound-station', 'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['showLabels' => false],
]);?>
    <div class="form-group">
        <?= Html::activeLabel($model, 'sound_station_name', ['label' =>'ชื่อ','class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-4">
            <?= $form->field($model, 'sound_station_name',['showLabels'=>false])->textInput([]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::activeLabel($model, 'counter_service_id', ['class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-10">
            <?= $form->field($model, 'counter_service_id',['showLabels'=>false])->checkBoxList(
                ArrayHelper::map((new \yii\db\Query())
                    ->select([
                        'tb_counter_service.counter_service_id',
	                    'CONCAT( \'(\', tb_counter_service_type.counter_service_type_name, \') \', tb_counter_service.counter_service_name ) AS counter_service_name'
                    ])
                    ->from('tb_counter_service')
                    ->innerJoin('tb_counter_service_type','tb_counter_service_type.counter_service_type_id = tb_counter_service.counter_service_type_id')
                    ->where(['tb_counter_service.counter_service_status' => 1])
                    ->all(),'counter_service_id','counter_service_name'),[
                'inline'=>false,
                'item' => function($index, $label, $name, $checked, $value) {

                    $return = '<div class="checkbox"><label style="font-size: 1em">';
                    $return .= Html::checkbox( $name, $checked,['value' => $value]);
                    $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                    $return .= '</label></div>';

                    return $return;
                }
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::activeLabel($model, 'sound_station_status', ['class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-4">
            <?= $form->field($model, 'sound_station_status',['showLabels'=>false])->RadioList(
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
    </div>

    <div class="form-group">
        <div class="col-sm-12 text-right">
            <?= Html::button(Icon::show('close').Yii::t('frontend','Close'),['class' => 'btn btn-default','data-dismiss' => 'modal']); ?>
            <?= Html::submitButton(Icon::show('save').Yii::t('frontend','Save'),['class' => 'btn btn-primary']); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
var table = $('#tb-sound-station').DataTable();
var \$form = $('#form-sound-station');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('#form-sound-station button[type="submit"]').button('loading');//loading btn
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
                setTimeout(function(){ 
                    \$btn.button('reset');
                }, 1000);//clear button loading
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
                \$btn.button('reset');
            }
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