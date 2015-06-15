<?php
return [
    'components' => [
        'request' => [
            'baseUrl' => '/backend',
            
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager'=>[
            'class'=>'yii\web\UrlManager',
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
        ],
    ],
];
