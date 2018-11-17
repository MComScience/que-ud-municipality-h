<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 22:31
 */
namespace homer\assets;

use yii\web\AssetBundle;

class BootboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootbox';

    public $js = [
        'bootbox.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}