<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
	'homeUrl' => '/',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'catalog/list',
	'language'   => 'en',
'sourceLanguage' => 'en',
'on beforeAction' => ['\pjhl\multilanguage\Start', 'run'],
    'components' => [
		'pm' => [
        'class' => '\nepster\perfectmoney\Api',
        'accountId' => '9547238',
        'accountPassword' => '1234sstr',
        'walletNumber' => 'U13958515',
        'merchantName' => 'Taraski',
        'alternateSecret' => '9N6038UkJfJvAzOwKVFbtHLZs',
        'resultUrl' => ['/merchant/perfect-money/result'],
        'successUrl' => ['/merchant/perfect-money/success'],
        'failureUrl' => ['/merchant/perfect-money/failure'],
    ],
	'request' => [
            'baseUrl' => '',
			 'class' => 'pjhl\multilanguage\components\AdvancedRequest',
        ],
		'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'class' => 'pjhl\multilanguage\components\AdvancedUrlManager',
            'rules' => [
			  //'catalog/list/<id:\w+>'=>'catalog/list',
			  'catalog/list/<id:\w+>/<name:[(\w)|(\-)|(\")|(\')]+>' => 'catalog/list',
			  'catalog/view/<id:\w+>/<name:[(\w)|(\-)|(\")|(\')]+>'=>'catalog/view',
			  'merchant/perfect-money/failure' => 'perfect-money/failure',
			  'merchant/perfect-money/result' => 'perfect-money/result',
			  'merchant/perfect-money/success' => 'perfect-money/success',
			  //'catalog/view/<id:\w+>'=>'catalog/view',
              //'catalog/list/<id:\w+>/<name:\[a-zA-Z]+>'=>'catalog/list',
			  
			],
		],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
        ],
    ],
    'params' => $params,
];
