<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 14/11/2561
 * Time: 22:21
 */
use homer\widgets\Modal;

$this->registerCss('.modal-header {padding: 15px;}');

Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    'options' => ['class' => 'modal modal-danger','tabindex' => false,],
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

Modal::end();