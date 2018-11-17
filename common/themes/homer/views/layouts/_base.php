<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 12:24
 */
use yii\helpers\Html;
use frontend\assets\AppAsset as FrontendAsset;
use backend\assets\AppAsset as BackendAsset;
use homer\assets\HomerAsset;

if (Yii::$app->id == 'app-frontend'){
    FrontendAsset::register($this);
}else{
    BackendAsset::register($this);
}
/* Theme Asset */
HomerAsset::register($this);
$this->registerMetaTag([
    'name' => 'description',
    'content' => 'ระบบบริหารจัดการคิวผู้ป่วย',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Yii::$app->name,
]);
$this->registerMetaTag([
    'name' => 'description',
    'content' => $this->title,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $this->title,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'MComScience',
]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

        <?= Html::csrfMetaTags() ?>
        <title><?php echo Html::encode(!empty($this->title) ? strtoupper($this->title).' | '.Yii::$app->name : Yii::$app->name); ?></title>
        <?php $this->head() ?>
    </head>
    <?= Html::beginTag('body',['class' => $class]); ?>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
    <?= Html::endTag('body') ?>
    </html>
<?php $this->endPage() ?>