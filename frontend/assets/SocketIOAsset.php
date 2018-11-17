<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 15/11/2561
 * Time: 14:50
 */
namespace frontend\assets;

use yii\web\AssetBundle;

class SocketIOAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'vendor/socket.io-client/dist/socket.io.js',
        'js/socket-client.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'homer\assets\ToastrAsset'
    ];
}