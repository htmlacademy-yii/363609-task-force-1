<?php
return [
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap'  => ['assetsAutoCompress'],
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                    'scope' => ['email']
                ],
            ],
        ],
        'assetsAutoCompress' => [
            'class'   => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled' => true,
            'readFileTimeout' => 3,
            'jsCompress'                => true,
            'jsCompressFlaggedComments' => true,
            'cssCompress' => true,
            'cssFileCompile'        => true,
            'cssFileCompileByGroups' => false,
            'cssFileRemouteCompile' => false,
            'cssFileCompress'       => true,
            'cssFileBottom'         => false,
            'cssFileBottomLoadOnJs' => false,
            'jsFileCompile'                 => false,
            'jsFileCompileByGroups'         => false,
            'jsFileRemouteCompile'          => false,
            'jsFileCompress'                => false,
            'jsFileCompressFlaggedComments' => false,
            'noIncludeJsFilesOnPjax' => false,
            'noIncludeCssFilesOnPjax' => false,
            'htmlFormatter' => [
                'class'         => 'skeeks\yii2\assetsAuto\formatters\html\TylerHtmlCompressor',
                'extra'         => false,
                'noComments'    => true,
                'maxNumberRows' => 50000,
            ],
        ],
    ],
];
