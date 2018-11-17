<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 22:29
 */

namespace homer\assets;

use yii\web\AssetBundle;

class AjaxCrudAsset extends AssetBundle
{
    public $sourcePath = '@homer/assets/ajaxcrud';

    public $css = [
        'css/ajaxcrud.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'homer\assets\HomerAsset',
    ];

    public function init()
    {
        $this->js = YII_DEBUG ? [
            'js/ModalRemote.js',
            'js/ajaxcrud.js',
        ] : [
            'js/ModalRemote.min.js',
            'js/ajaxcrud.min.js',
        ];
        parent::init();
    }
}