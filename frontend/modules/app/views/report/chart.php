<?php
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'รายงานกราฟ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hpanel">
    <?php echo $this->render('_tabs'); ?>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="panel-body">
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
                    <div class="col-sm-offset-2 col-sm-10">
                        <hr>
                        <?= Html::submitButton('แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('รีเซ็ต',['/app/report/chart'], ['class' => 'btn btn-danger']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <hr>

                <?php
                $items = [];
                if(count($data) > 0){
                    foreach($data as $key => $item){
                        $items[] = [
                            'label' => $item['service_group_name'],
                            'content' => $this->render('_content_chart',[
                                'model' => $item,
                            ]),
                            'active' => $key == 0 ? true : false,
                        ];
                    }
                }
                
                echo Tabs::widget([
                    'items' => $items,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

