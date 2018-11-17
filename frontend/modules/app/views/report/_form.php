<?php

use kartik\daterange\DateRangePicker;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use unclead\multipleinput\MultipleInput;
use kartik\widgets\TimePicker;
?>

<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
<div class="form-group">
    <?= Html::label('วันที่','', ['class'=>'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?php
        echo $form->field($model, 'date_range')->widget(DateRangePicker::classname(), [
            'presetDropdown'=>true,
            'hideInput'=>true,
            'startAttribute' => 'from_date',
            'endAttribute' => 'to_date',
            'pluginOptions'=>[
                'locale'=>['format' => 'Y-MM-DD'],
            ],
            'startInputOptions' => ['value' => isset($posted['from_date']) ? $posted['from_date'] : Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
            'endInputOptions' => ['value' => isset($posted['to_date']) ? $posted['to_date'] : Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
        ])->label(false);
        ?>
    </div>
</div>
<div class="form-group">
    <?= Html::label('ช่วงเวลา','', ['class'=>'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'times',['showLabels'=>false])->widget(MultipleInput::className(), [
            'max' => 20,
            'columns' => [
                [
                    'name'  => 'time_start',
                    'type'  => TimePicker::className(),
                    'title' => 'เริ่ม',
                    'value' => function($data) {
                        return $data['time_start'];
                    },
                    'options' => [
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                        ],
                        'options' => [
                            'readonly' => true,
                        ],
                    ]
                ],
                [
                    'name'  => 'time_end',
                    'type'  => TimePicker::className(),
                    'title' => 'สิ้นสุด',
                    'value' => function($data) {
                        return $data['time_end'];
                    },
                    'options' => [
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                        ],
                        'options' => [
                            'readonly' => true,
                        ],
                    ]
                ],
            ]
        ]);
        ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <hr>
        <?= Html::submitButton('แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('รีเซ็ต',['/app/report/index'], ['class' => 'btn btn-danger']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<hr>