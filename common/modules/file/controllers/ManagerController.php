<?php

namespace common\modules\file\controllers;

use alexantr\elfinder\ConnectorAction;
use Yii;
use yii\web\Controller;

class ManagerController extends Controller
{
    /** @var string */
    //public $layout = '//clear';

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::class,
                'options' => [
                    'disabledCommands' => ['netmount'],
                    'connectOptions' => [
                        'filter'
                    ],
                    'roots' => [
                        [
                            'driver' => 'LocalFileSystem',
                            'path' => Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR,
                            'URL' => Yii::getAlias('@web'),
                            'uploadDeny' => [
                                'text/x-php', 'text/php', 'application/x-php', 'application/php'
                            ],
                            'accessControl' => 'access',
                            'attributes' => [
                                [
                                    'pattern' => '!^/assets!',
                                    'hidden' => true
                                ],
                                [
                                    'pattern' => '!^/index.php!',
                                    'hidden' => true
                                ],
                                [
                                    'pattern' => '!^/index-test.php!',
                                    'hidden' => true
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
