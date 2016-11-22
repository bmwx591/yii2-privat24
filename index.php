<?php
require_once (__DIR__ . '/vendor/autoload.php');
require_once (__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$application = new yii\console\Application([
    'id' => 'test',
    'basePath' => dirname(__DIR__)
]);
$application->run();

use bmwx591\privat24\request\SendSMSRequest;
use bmwx591\privat24\Client;

$client = new Client([
    'id' => 123554,
    'password' => 'w8y8zjPWOH8oaIsqY207ff5l2dSMR5UQ',
    'isTest' => true
]);
$request = new SendSMSRequest([
    'method' => 'post',
    'paymentId' => 'payment_id_1',
    'properties' => [
        'phone' => '+380995038736',
        'phoneTo' => '+380964894718',
        'text' => 'Hello'
    ]
]);
$client->send($request);
