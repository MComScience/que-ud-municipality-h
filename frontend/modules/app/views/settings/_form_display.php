<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 21:30
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\ColorInput;
use kartik\icons\Icon;
use homer\widgets\ckeditor\CKEditor;
use homer\widgets\Table;
use yii\web\JsExpression;
use frontend\assets\SocketIOAsset;

SocketIOAsset::register($this);
$this->title = 'บันทึกจอแสดงผล';
$this->params['breadcrumbs'][] = ['label' => 'ตั้งค่า', 'url' => ['/app/setting/display']];
$this->params['breadcrumbs'][] = ['label' => 'ระบบคิว', 'url' => ['/app/setting/display']];
$this->params['breadcrumbs'][] = 'จอแสดงผล';

$this->registerCssFile("@web/css/display.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
$this->registerCssFile("@web/css/checkbox-bs.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
$this->registerCss($style);
$this->registerCss(<<<CSS
    .form-group {
        margin-bottom: 0px;
    }
CSS
);
?>
<?= \homer\sweetalert2\SweetAlert2::widget(['useSessionFlash' => true]) ?>
<div class="hpanel">
    <div class="panel-heading hbuilt">
        <h5><?= $this->title; ?></h5>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'id' => 'form-display']); ?>

        <div class="form-group">
            <?= Html::activeLabel($model, 'display_name', ['label' => 'ชื่อจอแสดงผล', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'display_name', ['showLabels' => false])->textInput(['placeholder' => 'ชื่อจอแสดงผล']); ?>
            </div>

            <?= Html::activeLabel($model, 'page_length', ['label' => 'จำนวนแถวที่แสดง', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'page_length', ['showLabels' => false])->textInput(['placeholder' => 'จำนวนแถวที่แสดง']); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'que_column_length', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'que_column_length', ['showLabels' => false])->textInput(['placeholder' => '']); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">
                <p>
                    <span class="badge badge-danger">ส่วนหัวตาราง</span>
                </p>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'text_th_left', ['label' => 'ข้อความส่วนหัวตาราง 1', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'text_th_left', ['showLabels' => false])->textInput(['placeholder' => 'เช่น หมายเลขช่อง']); ?>
            </div>

            <?= Html::activeLabel($model, 'text_th_right', ['label' => 'ข้อความส่วนหัวตาราง 2', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'text_th_right', ['showLabels' => false])->textInput(['placeholder' => 'เช่น ช่อง,ห้อง,โต๊ะ']); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'text_hold', ['label' => 'ข้อความตารางพักคิว', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'text_hold', ['showLabels' => false])->textInput(['placeholder' => 'เช่น คิวที่เรียกไปแล้ว']); ?>
            </div>
        </div>
        <div class="form-group" style="display: none;">
            <?= Html::activeLabel($model, 'color_th_left', ['label' => 'สีตัวอักษรข้อความ 1', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'color_th_left', ['showLabels' => false])->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select color ...'],
                ]); ?>
            </div>

            <?= Html::activeLabel($model, 'color_th_right', ['label' => 'สีตัวอักษรข้อความ 2', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'color_th_right', ['showLabels' => false])->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select color ...'],
                ]); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">
                <p>
                    <span class="badge badge-danger">ส่วนหัวตารางคิวล่าสุด</span>
                </p>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'text_th_lastq_left', ['label' => 'ข้อความส่วนหัวตาราง 1', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'text_th_lastq_left', ['showLabels' => false])->textInput(['placeholder' => '']); ?>
            </div>

            <?= Html::activeLabel($model, 'text_th_lastq_right', ['label' => 'ข้อความส่วนหัวตาราง 2', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'text_th_lastq_right', ['showLabels' => false])->textInput(['placeholder' => '']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">
                <p><span class="badge badge-danger">สีพื้นหลัง</span></p>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'background_color', ['label' => 'สีพื้นหลังหน้าจอ', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'background_color', ['showLabels' => false])->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select color ...'],
                ]); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">
                <p><span class="badge badge-danger">CSS</span></p>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'color_code', ['label' => 'โค้ดสี', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'color_code', ['showLabels' => false])->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select color ...'],
                ]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'display_css', ['label' => 'CSS', 'class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-8">
                <?= $form->field($model, 'display_css', ['showLabels' => false])->widget(CKEditor::classname(), [
                    'options' => ['rows' => 12],
                    'preset' => 'custom',
                    'clientOptions' => [
                        'extraPlugins' => 'font,justify,pbckcode,preview,autogrow',
                        'toolbarGroups' => [
                            ['groups' => ['mode']],
                            //['name' => 'document','groups' => ['mode' ]],
                            ['name' => 'pbckcode', ['modes' => [['HTML', 'html'], ['CSS', 'css'], ['PHP', 'php'], ['JS', 'javascript']],]],
                            ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                        ],
                    ]
                ])->hint('<span class="badge badge-danger">Notice !</span><span> click ที่ไอคอน <i class="fa fa-code"></i> เพื่อแก้ไข</span>'); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">
                <p>
                    <span class="badge badge-danger">ข้อความ</span>
                </p>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'text_top_left', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-8">
                <?= $form->field($model, 'text_top_left', ['showLabels' => false])->widget(CKEditor::className(), [
                    'options' => [
                        'rows' => 2,
                        'value' => $model->isNewRecord ? '<h1><span style="color:#62cb31"><strong>เรียกคิวห้องยา</strong></span></h1>' : $model['text_top_left']
                    ],
                    'preset' => 'basic',
                    'clientOptions' => [
                        'extraPlugins' => 'font,justify,pbckcode,preview,autogrow',
                        'toolbarGroups' => [
                            ['groups' => ['mode']],
                            ['name' => 'styles']
                        ],
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group" style="display: none;">
            <?= Html::activeLabel($model, 'text_top_center', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-8">
                <?= $form->field($model, 'text_top_center', ['showLabels' => false])->widget(CKEditor::className(), [
                    'options' => [
                        'rows' => 2,
                        'value' => $model->isNewRecord ? '<h1><span style="color:#62cb31"><strong>ห้องยา</strong></span></h1>' : $model['text_top_center']
                    ],
                    'preset' => 'basic',
                    'clientOptions' => [
                        'extraPlugins' => 'font,justify,pbckcode,preview,autogrow',
                        'toolbarGroups' => [
                            ['groups' => ['mode']],
                            ['name' => 'styles']
                        ],
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'text_top_right', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-8">
                <?= $form->field($model, 'text_top_right', ['showLabels' => false])->widget(CKEditor::className(), [
                    'options' => [
                        'rows' => 2,
                        'value' => $model->isNewRecord ? '<h1><span style="color:#62cb31"><strong>คิวล่าสุด</strong></span></h1>' : $model['text_top_right']
                    ],
                    'preset' => 'basic',
                    'clientOptions' => [
                        'extraPlugins' => 'font,justify,pbckcode,preview,autogrow',
                        'toolbarGroups' => [
                            ['groups' => ['mode']],
                            ['name' => 'styles']
                        ],
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'counter_service_id', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-10">
                <?= $form->field($model, 'counter_service_id', ['showLabels' => false])->checkBoxList($model->counterServiceData, [
                    'inline' => false,
                    'item' => function ($index, $label, $name, $checked, $value) {

                        $return = '<div class="checkbox"><label style="font-size: 1em">';
                        $return .= Html::checkbox($name, $checked, ['value' => $value]);
                        $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                        $return .= '</label></div>';

                        return $return;
                    }
                ]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'service_id', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-10">
                <?= $form->field($model, 'service_id', ['showLabels' => false])->checkBoxList($model->serviceData, [
                    'inline' => false,
                    'item' => function ($index, $label, $name, $checked, $value) {

                        $return = '<div class="checkbox"><label style="font-size: 1em">';
                        $return .= Html::checkbox($name, $checked, ['value' => $value]);
                        $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                        $return .= '</label></div>';

                        return $return;
                    }
                ]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'show_que_hold', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-4">
                <?= $form->field($model, 'show_que_hold', ['showLabels' => false])->RadioList(
                    [0 => 'ไม่แสดง', 1 => 'แสดง'], [
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
        <div class="form-group">
            <?= Html::activeLabel($model, 'display_status', ['class' => 'col-sm-2 control-label']) ?>
            <div class="col-sm-4">
                <?= $form->field($model, 'display_status', ['showLabels' => false])->RadioList(
                    [0 => 'ปิดใช้งาน', 1 => 'เปิดใช้งาน'], [
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
        <div class="form-group">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">ตัวอย่างจอแสดงผล</h3>
                    </div>
                    <div class="panel-body"
                         style="background-color: <?= $model->isNewRecord && empty($model['background_color']) ? '#204d74' : $model['background_color']; ?>;">
                        <div class="row">
                            <div class="col-sm-4 text-center">
                                <?= $model->isNewRecord ? '<h1><span style="color:#62cb31"><strong>เรียกคิวห้องยา</strong></span></h1>' : $model->text_top_left ?>
                            </div>
                            <div class="col-sm-4 text-center">
                                <?= $model->isNewRecord ? '<h1><span style="color:#62cb31"><strong>ห้องยา</strong></span></h1>' : $model->text_top_center ?>
                            </div>
                            <div class="col-sm-4 text-center">
                                <?= $model->isNewRecord ? '<h1><span style="color:#62cb31"><strong>คิวล่าสุด</strong></span></h1>' : $model->text_top_right ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <?php
                                // TODO table last q
                                $qnums = [];
                                $que_column_length = !empty($model['que_column_length']) ? $model['que_column_length'] : 1;
                                for ($i = 1; $i <= $que_column_length; $i++) {
                                    $qnums[] = 'A00' . $i;
                                }
                                echo Table::widget([
                                    'tableOptions' => ['class' => 'table', 'id' => 'tb-display'],
                                    'beforeHeader' => [
                                        [
                                            'columns' => [
                                                ['content' => ($model->isNewRecord ? 'หมายเลข' : $model['text_th_left']), 'options' => ['style' => 'text-align: center;width: 50%;', 'class' => 'th-left']],
                                                ['content' => ($model->isNewRecord ? 'ช่อง' : $model['text_th_right']), 'options' => ['style' => 'text-align: center;width: 50%;', 'class' => 'th-right']],
                                            ],
                                        ],
                                    ],
                                    'columns' => [
                                        [
                                            ['content' => implode('|', $qnums), 'options' => ['class' => 'td-left']],
                                            ['content' => '1', 'options' => ['class' => 'td-right']],
                                        ],
                                        [
                                            ['content' => implode('|', $qnums), 'options' => ['class' => 'td-left']],
                                            ['content' => '2', 'options' => ['class' => 'td-right']],
                                        ],
                                        [
                                            ['content' => implode('|', $qnums), 'options' => ['class' => 'td-left']],
                                            ['content' => '3', 'options' => ['class' => 'td-right']],
                                        ],
                                    ],
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-4">
                                <?php
                                echo Table::widget([
                                    'tableOptions' => ['class' => 'table', 'id' => 'tb-lastque'],
                                    'beforeHeader' => [
                                        [
                                            'columns' => [
                                                ['content' => ($model->isNewRecord ? '#' : $model['text_th_lastq_left']), 'options' => ['style' => 'text-align: center;width: 50%;', 'class' => 'th-left']],
                                                ['content' => ($model->isNewRecord ? 'หมายเลข' : $model['text_th_lastq_right']), 'options' => ['style' => 'text-align: center;width: 50%;', 'class' => 'th-right']],
                                            ],
                                        ],
                                    ],
                                    'columns' => [
                                        [
                                            ['content' => 'A', 'options' => ['class' => 'td-left']],
                                            ['content' => 'A001', 'options' => ['class' => 'td-right']],
                                        ],
                                        [
                                            ['content' => 'B', 'options' => ['class' => 'td-left']],
                                            ['content' => 'B001', 'options' => ['class' => 'td-right']],
                                        ],
                                        [
                                            ['content' => 'C', 'options' => ['class' => 'td-left']],
                                            ['content' => 'C001', 'options' => ['class' => 'td-right']],
                                        ],
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                if ($model['show_que_hold'] == 1) {
                                    echo Table::widget([
                                        'tableOptions' => ['class' => 'table', 'id' => 'tb-hold'],
                                        'columns' => [
                                            [
                                                ['content' => ($model->isNewRecord ? '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                                            คิวที่เรียกไปแล้ว
                                            </div>' : '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                                            ' . $model['text_hold'] . '
                                            </div>'), 'options' => ['class' => '', 'style' => 'width: 40%;']],
                                                ['content' => '<marquee>A004 | A005</marquee>', 'options' => ['class' => 'td-right', 'style' => 'width: 60%;']],
                                            ],
                                        ],
                                        'datatableOptions' => [
                                            "clientOptions" => [
                                                "dom" => "t",
                                                "responsive" => true,
                                                "autoWidth" => false,
                                                "deferRender" => true,
                                                "ordering" => false,
                                                "pageLength" => 1,
                                                "columns" => [
                                                    ["data" => "text", "defaultContent" => '<div class="ribbon ribbon-right ribbon-shadow ribbon-border-dash ribbon-round ribbon-hold uppercase" style="width:100%;padding: 0.2em 1em;">
                                                ' . $model['text_hold'] . '
                                                </div>', "className" => "text-center", "orderable" => false],
                                                    ["data" => "que_number", "defaultContent" => "", "className" => "text-center td-right", "orderable" => false],
                                                ],
                                                "language" => [
                                                    "loadingRecords" => "กำลังโหลดข้อมูล...",
                                                    "emptyTable" => "ไม่มีข้อมูลคิว"
                                                ],
                                                'initComplete' => new JsExpression ('
                                                function () {
                                                    var api = this.api();
                                                    $("#tb-hold thead").hide();
                                                }
                                            '),
                                            ],
                                            'clientEvents' => [
                                                'error.dt' => 'function ( e, settings, techNote, message ){
                                                e.preventDefault();
                                                console.warn("error message",message);
                                            }'
                                            ],
                                        ],
                                    ]);
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::a(Icon::show('close') . ' ปิดหน้าต่าง', ['/app/settings/display'], ['class' => 'btn btn-default']); ?>
                <?php if (!$model->isNewRecord): ?>
                    <?= Html::a(Icon::show('refresh') . ' รีเซ็ต', ['/app/settings/update-display', 'id' => $model['display_ids']], ['class' => 'btn btn-danger']); ?>
                <?php endif; ?>
                <?= Html::submitButton(Icon::show('desktop') . 'แสดงตัวอย่าง', ['class' => 'btn btn-success']) ?>
                <?= Html::button(Icon::show('save') . 'บันทึก', ['class' => 'btn btn-primary activity-save']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$this->registerJS(<<<JS
$('button.activity-save').on('click',function(){
    var \$form = $('#form-display');
    var data = new FormData($(\$form)[0]);//\$form.serialize();
    \$.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        async: false,
        processData: false,
        contentType: false,
        success: function (res) {
            if(res.validate != null){
                $.each(res.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }else{
                socket.emit('update-display', res);
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
                window.location.href = res.url;
            }
        },
        error: function(jqXHR, errMsg) {
            swal('Oops...',errMsg,'error');
        }
    });
});
JS
);
?>