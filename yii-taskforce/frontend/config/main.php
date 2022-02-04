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
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\v1\Module',
            'modules' => [
                'v1' => [
                    'class' => 'frontend\modules\api\v1\Module'
                ]
            ]
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true]
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'enableStrictParsing' => false,
            'rules' => [
                'login' => 'site/login',
                'tasks' => 'tasks/index',
                'task/view/<id:.+>' => 'tasks/view',
                'users' => 'users/index',
                'user/view/<id:.+>' => 'users/view',
                'registration' => 'registration/index',
                'task/create' => 'tasks/create',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/v1/messages'
                ],
                'my-task' => 'user-task/index',
                'my-task/cancel' => 'user-task/canceled',
                'my-task/active' => 'user-task/in-work',
                'my-task/done' => 'user-task/completed',
                'my-task/expire' => 'user-task/expired'
            ],
        ],
    ],
    'params' => $params,
];
