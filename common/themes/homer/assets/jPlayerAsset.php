<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 17/11/2561
 * Time: 15:01
 */
namespace homer\assets;

use yii\web\AssetBundle;

class jPlayerAsset extends AssetBundle
{
    public $sourcePath = '@bower/jplayer/dist';

    public $js = [
        'jplayer/jquery.jplayer.min.js',
        'add-on/jplayer.playlist.min.js',
        'add-on/jquery.jplayer.inspector.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];
}