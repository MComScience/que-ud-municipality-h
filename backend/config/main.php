<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'homeUrl' => '/admin',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityCookie' => [
                'name'     => '_backendIdentity',
                'path'     => '/admin',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'name' => 'BACKENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path'     => '/admin',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'fileStorage' => [
            'class' => 'trntv\filekit\Storage',
            'baseUrl' => '@web/uploads',
            'filesystem' => [
                'class' => 'common\components\filesystem\LocalFlysystemBuilder',
                'path' => '@webroot/uploads'
            ],
            'as log' => [
                'class' => 'common\behaviors\FileStorageLogBehavior',
                'component' => 'fileStorage'
            ],
        ],
    ],
    'params' => $params,
];
