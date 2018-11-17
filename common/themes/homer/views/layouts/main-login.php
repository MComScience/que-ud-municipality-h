<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 21:04
 */

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use homer\assets\HomerAsset;

AppAsset::register($this);
HomerAsset::register($this);
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
    <body class="blank">
    <?php $this->beginBody() ?>
    <!-- Simple splash screen-->
    <div class="splash">
        <div class="color-line"></div>
        <div class="splash-title"><h1><?= Yii::$app->name; ?></h1>
            <p></p>
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
    </div>

    <div class="color-line"></div>
    <div class="login-container">
        <?= Alert::widget() ?>
        <?= $content ?>
        <div class="row">
            <div class="col-md-12 text-center">
                Responsive WebApp <br/> 2018 Copyright MComScience.
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>