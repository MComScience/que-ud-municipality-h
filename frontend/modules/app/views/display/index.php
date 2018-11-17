<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = Yii::t('frontend', 'Display');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <?php foreach ($displays as $display) : ?>
        <div class="col-md-3">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="text-center">
                        <p class="font-bold text-success"><?= $display['display_name'] ?></p>
                        <div class="m">
                            <i class="pe-7s-monitor fa-5x"></i>
                        </div>
                        <?= Html::a('OPEN',['/app/display/view','id' => $display['display_ids']],['class' => 'btn btn-primary btn-sm','target' => '_blank']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

