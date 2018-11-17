<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 22:45
 */
namespace homer\assets;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle
{
    public $sourcePath = '@homer/assets/dist/vendor/toastr';

    public $css = [
        'build/toastr.min.css',
    ];

    public $js = [
        'build/toastr.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}