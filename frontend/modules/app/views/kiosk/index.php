<?php
/* @var $this yii\web\View */

use yii\bootstrap\Tabs;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('frontend', 'Print Ticket');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile("@web/css/checkbox-bs.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
]);
$this->registerCss(<<<CSS
    .form-horizontal .radio,
    .form-horizontal .checkbox,
    .form-horizontal .radio-inline,
    .form-horizontal .checkbox-inline {
        display: inline-block;
    }
CSS
);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . ';', View::POS_HEAD);
?>
<div class="hpanel">
    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('frontend', 'Print Ticket'),
                'content' => $this->render('_content_ticket',[
                    'model' => $model,
                    'services' => $services,
                    'modelServiceGroup' => $modelServiceGroup,
                    'provider' => $provider,
                    'modelDevice' => $modelDevice,
                ]),
                'active' => true,
                'options' => ['id' => 'tab-print-ticket'],
            ],
            [
                'label' => Yii::t('frontend', 'Queue List').'&nbsp;'. Html::tag('span', '0', ['class' => 'label label-success', 'id' => 'count-qdata']),
                'content' => $this->render('_content_que_list'),
                'options' => ['id' => 'tab-que-list'],
            ]
        ],
        'encodeLabels' => false
    ]);
    ?>
</div>
<?php
echo $this->render('modal');
?>
