<?php
namespace bmwx591\privat24;

require_once (__DIR__ . '/vendor/autoload.php');
require_once (__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
Yii::setAlias('@yii', __DIR__. '/vendor/yiisoft/yii2');

$application = new yii\web\Application();
$application->run();

use bmwx591\privat24\request\properties\SendSMSProperties;
use bmwx591\privat24\request\Request;
use bmwx591\privat24\request\SendSMSRequest;

$client = new Client([
    'id' => '<id>',
    'password' => '<password>',
    'isTest' => true
]);
$request = new SendSMSRequest([
    'paymentId' => 'pay_online_1',
    'properties' => [
        'phone' => '+380995038736',
        'phoneTo' => '+380964894718',
        'text' => 'Hello'
    ]
]);
$client->send($request);
