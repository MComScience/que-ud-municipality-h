<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 12:30
 */
namespace homer\assets;

use yii\web\AssetBundle;

class HomerAsset extends AssetBundle
{
    public $sourcePath = '@homer/assets/dist';

    public $css = [
        'vendor/metisMenu/dist/metisMenu.css',
        'vendor/animate.css/animate.css',
        'fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css',
        'fonts/pe-icon-7-stroke/css/helper.css',
        'styles/static_custom.css',
        'styles/style.css'
    ];

    public $js = [
        'vendor/slimScroll/jquery.slimscroll.min.js',
        'vendor/metisMenu/dist/metisMenu.min.js',
        'vendor/sparkline/index.js',
        'scripts/homer.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'homer\assets\FontAwesomeAsset',
    ];
}