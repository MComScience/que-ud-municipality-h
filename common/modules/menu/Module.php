<?php

namespace common\modules\menu;
use Yii;
/**
 * menu module definition class
 */
class Module extends \yii\base\Module {

    public $defaultRoute = 'default/menu-order';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\menu\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        //$this->layout = 'left-menu.php';

        if (!isset(Yii::$app->i18n->translations['menu'])) {
            Yii::$app->i18n->translations['menu'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@common/modules/menu/messages',
            ];
        }
        
        parent::init();

        // custom initialization code goes here
    }

}
