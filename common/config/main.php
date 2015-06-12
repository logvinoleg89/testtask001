<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%rbac_auth_item}}',
            'itemChildTable' => '{{%rbac_auth_item_child}}',
            'assignmentTable' => '{{%rbac_auth_assignment}}',
            'ruleTable' => '{{%rbac_auth_rule}}'
        ],
        'commandBus' => [
            'class' => '\trntv\tactician\Tactician',
            'commandNameExtractor' => '\League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor',
            'methodNameInflector' => '\League\Tactician\Handler\MethodNameInflector\HandleInflector',
            'commandToHandlerMap' => [
                'common\commands\command\SendEmailCommand' => '\common\commands\handler\SendEmailHandler',
                'common\commands\command\AddToTimelineCommand' => '\common\commands\handler\AddToTimelineHandler',
            ]
        ],
        'i18n' => [
            'translations' => [
                'app'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                ],
                '*'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                    'fileMap'=>[
                        'common'=>'common.php',
                        'backend'=>'backend.php',
                        'frontend'=>'frontend.php',
                    ]
                ],
                /* Uncomment this code to use DbMessageSource
                 '*'=> [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable'=>'{{%i18n_source_message}}',
                    'messageTable'=>'{{%i18n_message}}',
                    'enableCaching' => YII_ENV_DEV,
                    'cachingDuration' => 3600
                ],
                */

            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => true,
            'appendTimestamp' => YII_ENV_DEV
        ]
    ],
];
