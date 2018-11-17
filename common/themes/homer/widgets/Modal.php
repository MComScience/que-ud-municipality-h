<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 22:27
 */
namespace homer\widgets;

use yii\helpers\Html;
use yii\bootstrap\Modal as BaseModal;
use homer\assets\AjaxCrudAsset;

class Modal extends BaseModal
{
    public function run()
    {
        echo "\n" . $this->renderBodyEnd();
        echo "\n" . $this->renderFooter();
        echo "\n" . Html::endTag('div'); // modal-content
        echo "\n" . Html::endTag('div'); // modal-dialog
        echo "\n" . Html::endTag('div');

        $this->registerPlugin('modal');
        AjaxCrudAsset::register($this->getView());
    }
}