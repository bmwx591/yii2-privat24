<?php
require_once (__DIR__ . '/vendor/autoload.php');
require_once (__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$application = new yii\console\Application([
    'id' => 'test',
    'basePath' => dirname(__DIR__)
]);
$application->run();

use bmwx591\privat24\request\SendSMSPayRequest;
use bmwx591\privat24\request\MobileReplenishmentRequest;
use bmwx591\privat24\Client;

$client = new Client([
    'id' => 1,
    'password' => '11111111111111111111111111111111',
    'isTest' => true
]);
$sendSmsRequest = new SendSMSPayRequest([
    'paymentId' => 'payment_id_1',
    'properties' => [
        'phone' => '+380995038736',
        'phoneTo' => '+380964894718',
        'text' => 'Hello'
    ]
]);
$mobileReplenishmentRequest = new MobileReplenishmentRequest([
    'paymentId' => 'payment_id_2',
    'properties' => [
        'phone' => '+380995038736',
        'amt' => 30.25
    ]
]);
//var_dump($request);die;
print_r($client->send($sendSmsRequest));
print_r($client->send($mobileReplenishmentRequest));
die;
