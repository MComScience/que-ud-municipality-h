<?php
namespace homer\widgets\nestable;

use yii\web\AssetBundle;

class NestableAsset extends AssetBundle 
{
	public $sourcePath = '@homer/widgets/nestable/assets';

    public $js = [
		'js/jquery.nestable.js'
	];
	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}