<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/canseeparams.php'
);

return [
    'id' => 'app-wechats',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'name' => '趣途文化',
    'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'wechat\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-wechats',
            'cookieValidationKey' => 'adafdasfsadfas-wechats',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-wechats', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the wechats
            'name' => 'advanced-wechats',
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
        
    ],
    'params' => $params,
];
