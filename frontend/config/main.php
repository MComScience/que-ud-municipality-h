<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','languagepicker'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityCookie' => [
                'name'     => '_frontendIdentity',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'name' => 'FRONTENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
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
                'dashboard' => 'site/index',
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
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@homer/views',
                    '@dektrium/user/views' => '@homer/user/views',
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'user/registration/*',
            'user/security/*',
            'user/recovery/*',
            'app/calling/play-sound',
            'app/calling/autoplay-media',
            'app/calling/update-status-called',
            'app/display/*',
            'qrcode/*',
            'app/kiosk/service',
            'app/kiosk/select-device',
            'app/kiosk/register',
            'app/kiosk/register-nocard',
            'app/kiosknocard/service',
            'app/kiosknocard/select-device',
            'app/kiosknocard/register',
            'app/kiosknocard/register-nocard',
            'app/kiosknocard/print-ticket',
            'app/kiosk/decode-data',
            'app/kiosk/print-ticket',
            'app/kiosk/create-device'
        ]
    ],
    'params' => $params,
];
