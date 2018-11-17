<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 16/11/2561
 * Time: 7:06
 */

use yii\bootstrap\BootstrapAsset;
use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;

BootstrapAsset::register($this);
JqueryAsset::register($this);

$this->registerCssFile("@web/css/80mm.css", [
    'depends' => [BootstrapAsset::className()],
]);

$baseUrl = \Yii::$app->keyStorage->get('baseUrlScan', Url::base(true));

$this->registerCss("
div#bcTarget {
    overflow: hidden !important;
    padding-top: 10px !important;
}
div#qrcode img {
    display: none;
}
.qwaiting > h4 {
    margin-top: 0px !important;
    margin-bottom: 0px !important;
    text-align: center !important;
}
#table-time td {
    /*border-top: 1px solid #fff !important;*/
}
");

$y = date('Y') + 543;

$this->registerJs('var barcodeOptions = ' . \Yii::$app->keyStorage->get('barcode-options', '{format: "CODE128"};'), View::POS_HEAD);

$this->title = 'บัตรคิว';
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <!-- 80mm -->
    <?php
    for ($x = 0; $x < $modelService['print_copy_qty']; $x++) {
        echo "<center>";
        echo $template;
        if ($modelService['print_copy_qty'] > 1) {
            echo '<div class="row" style="margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; width:80mm">
        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:5px 20px 0px 20px">
            <div class="col-xs-12" style="padding:0; text-align:left">
                <div class="col-xs-12" style="border-top:dashed 1px #ddd; padding:4px 0px 3px 0px">
                </div>
            </div>
        </div>
        </div>';
        }
        echo "</center>";
    }
    ?>
    <?php
    $this->registerJsFile(
        '@web/js/JsBarcode.all.min.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]
    );
    $this->registerJsFile(
        '@web/js/jquery-qrcode-0.14.0.min.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]
    );
    $this->registerJs(<<<JS
$('#bcTarget').remove();
});
$(window).on('load', function() {
    //Barcode
    JsBarcode(".bcTarget", "{$modelQue->que_num}", barcodeOptions);
    $('.qrcode').qrcode({
        render: 'canvas',
        text: "{$baseUrl}/qrcode/mobile-view?id={$modelQue->que_ids}",
        image: null,
        mode: 0,
        background: null,
        size: 100,
        fill: '#000',
        mSize: 0.1,
        mPosX: 0.5,
        mPosY: 0.5,
        fontname: 'sans',
        fontcolor: '#000',
    });
    window.print();
    window.onafterprint = function(){
        window.close();
    }
JS
    ); ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>