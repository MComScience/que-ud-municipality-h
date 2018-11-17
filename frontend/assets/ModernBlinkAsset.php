<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 17/11/2561
 * Time: 15:47
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ModernBlinkAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/vendor/modern-blink';
    public $js = [
        'dist/jquery.modern-blink.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}