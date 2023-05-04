<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
//'https://master--startling-marigold-e6b21a.netlify.app/frontend/web/api/auth/login'
return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'oneId' => [
            'class' => 'frontend\components\OneId',
            'authorizationUrl' => 'https://sso.egov.uz/sso/oauth/Authorization.do',
            'clientId' => 'ima_uz',
            'clientSecret' => '590yRG+aflfeBMnR0/jvkg==',
            'scope' => 'ima_uz',
            'responseType' => 'one_code',
            'state' => 'eyJtZXRob2QiOiJJRFBXIn0=',
            'grantType' => 'one_authorization_code',
            'redirectUrl' => 'https://ima-dev.uz/frontend/web/api/auth/login',
        ],
       /* 'request' => [
            'csrfParam' => '_csrf-frontend',
        ],*/
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'frontend\models\ImaUsers',
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'api' => [
            'class' => 'frontend\modules\api\ApiModule',
        ],
    ],
    'params' => $params,
];
