<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-expert',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'expert\controllers',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
       /* 'request' => [
            'csrfParam' => '_csrf-expert',
        ],*/
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => \expert\models\ExpertUser::class,
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-expert', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the expert
            'name' => 'advanced-expert',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
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
    'modules' => [
        'v1' => [
            'class' => \expert\modules\v1\ApiModule::class,
        ],
    ],
    'params' => $params,
];
