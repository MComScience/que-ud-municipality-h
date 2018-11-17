<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 20:58
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use trntv\filekit\widget\Upload;
use kartik\widgets\Select2;
use yii\bootstrap\BootstrapAsset;
use kartik\icons\Icon;
use homer\sweetalert2\assets\SweetAlert2Asset;
SweetAlert2Asset::register($this);

$this->title = 'บันทึกบัตรคิว';
$this->params['breadcrumbs'][] = ['label' => 'ตั้งค่า', 'url' => ['/app/setting/ticket']];
$this->params['breadcrumbs'][] = ['label' => 'ระบบคิว', 'url' => ['/app/setting/ticket']];
$this->params['breadcrumbs'][] = 'บัตรคิว';

$this->registerCssFile("@web/css/checkbox-bs.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);

$this->registerCssFile("@web/css/ticket.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);

$this->registerCssFile("@web/css/80mm.css", [
    'depends' => [BootstrapAsset::className()],
]);
?>
<?= \homer\sweetalert2\SweetAlert2::widget(['useSessionFlash' => true]); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'form-ticket', 'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['showLabels' => false],
            ]);?>
            <div class="form-group">
                <?= Html::activeLabel($model, 'logo', ['label' => 'โลโก้บัตรคิว','class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'logo')->widget(Upload::classname(), [
                        'url'=>['file-upload'],
                    ])->hint('<span class="text-warning">ภาพที่จะนำไปแสดงบนบัตรคิว</span>') ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::activeLabel($model, 'hos_name_th', ['label' => 'ชื่อ รพ. (ไทย)','class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'hos_name_th',['showLabels'=>false])->textInput([
                        'placeholder' => 'ชื่อ รพ. (ไทย)'
                    ]); ?>
                </div>

                <?= Html::activeLabel($model, 'hos_name_en', ['label' => 'ชื่อ รพ. (อังกฤษ)','class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'hos_name_en',['showLabels'=>false])->textInput([
                        'placeholder' => 'ชื่อ รพ. (อังกฤษ)'
                    ]); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::activeLabel($model, 'barcode_type', ['label' => 'รหัสบาร์โค้ด','class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'barcode_type')->widget(Select2::classname(), [
                        'data' => [
                            'codabar' => 'codabar',
                            'code11' => 'code11',
                            'code39' => 'code39',
                            'code93' => 'code93',
                            'code128' => 'code128',
                            'ean8' => 'ean8',
                            'ean13' => 'ean13',
                            'std25' => 'std25',
                            'int25' => 'int25',
                            'msi' => 'msi',
                            'datamatrix' => 'datamatrix'
                        ],
                        'options' => ['placeholder' => 'เลือกรหัสโค้ด...','value' => $model->isNewRecord ? 'code128' : $model['barcode_type']],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'theme' => Select2::THEME_BOOTSTRAP,
                    ])->hint('<span class="text-danger">แนะนำให้ใช้โค้ด code128 </span>') ?>
                </div>

                <?= Html::activeLabel($model, 'ticket_status', ['class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'ticket_status',['showLabels'=>false])->RadioList(
                        [0 => 'ปิดใช้งาน', 1 => 'เปิดใช้งาน'],[
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
                <?= Html::activeLabel($model, 'template', ['label' => 'บัตรคิว','class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'template')->textarea([
                        'value' => $model->isNewRecord || empty($model['template']) ? $model->defaultTemplate : $model['template'],
                    ])->hint('<span class="text-danger">หมายเหตุ. ห้าม!!! เปลี่ยนข้อความที่มีเครื่องหมาย {} </span>') ?>
                </div>
                <?= Html::activeLabel($model, 'template', ['label' => 'ตัวอย่างบัตรคิว','class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <div id="editor-preview">
                        <?= $model->isNewRecord || empty($model['template']) ? $model->exampleTemplate : $model->ticketPreview; ?>
                    </div>
                </div>
            </div>
            <?= Html::activeHiddenInput($model,'default_template',['value' => $model->defaultTemplate]) ?>

            <div class="form-group">
                <div class="col-sm-12" style="text-align: right;">
                    <?= Html::a(Icon::show('close').Yii::t('frontend','Close'),['/app/settings/ticket'],['class' => 'btn btn-default','data-dismiss' => 'modal']); ?>
                    <?= Html::submitButton(Icon::show('save').Yii::t('frontend','Save'),['class' => 'btn btn-primary']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$this->registerJsFile(
    '@web/vendor/ckeditor/ckeditor.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/vendor/moment/moment.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/vendor/moment/locale/th.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs(<<<JS
var d = new Date();
var y = d.getFullYear() + 543;
moment.locale('th');
var editor  = CKEDITOR.inline( 'tbticket-template',{
    contenteditable: true,
    language: 'th',
    extraPlugins: 'sourcedialog',
    uiColor: '#f1f3f6'
});

editor.on('change',function(){
    var data = editor.getData()
    .replace('{hos_name_th}', $('#tbticket-hos_name_th').val())
    .replace('{q_hn}','0008962222')
    .replace('{pt_name}','Hospital')
    .replace('{q_num}','A001')
    .replace('{pt_visit_type}','ผู้ป่วยนัดหมาย')
    .replace('{sec_name}','แผนกห้องยา')
    .replace('{time}',moment().format("D MMM ") + (y.toString()).substr(2))
    .replace('{user_print}','Admin Hospital');
    data.replace('{hos_name_th}', $('#tbticket-hos_name_th').val())
    $('#editor-preview').html(data);
    editor.updateElement();
});
JS
);
?>
<?php
if(Yii::$app->controller->action->id == 'update-ticket'){
    $this->registerJs(<<<JS
//var table = $('#tb-ticket').DataTable();
var \$form = $('#form-ticket');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('button[type="submit"]').button('loading');//loading btn
    \$.ajax({
        url: \$form.attr('action'),
        type: \$form.attr('method'),
        data: data,
        success: function (data) {
            if(data.status == '200'){
                $('#ajaxCrudModal').modal('hide');//hide modal
               // table.ajax.reload();//reload table
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function(){ 
                    \$btn.button('reset');
                }, 1000);//clear button loadingstatus
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
}
?>