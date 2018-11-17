<?php
namespace common\assets;

use yii\web\AssetBundle;

class SystemInformationAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/system-information';
    public $js = [
        'index.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'common\assets\Flot',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}