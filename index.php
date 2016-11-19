<?php
namespace bmwx591\privat24;

require_once (__DIR__ . '/vendor/autoload.php');
require_once (__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

use bmwx591\privat24\request\properties\SendSMSProperties;
use bmwx591\privat24\request\Request;

$client = new Client([
    'id' => 123554,
    'password' => 'w8y8zjPWOH8oaIsqY207ff5l2dSMR5UQ',
    'isTest' => true
]);

$properties = new SendSMSProperties([
    'phone' => '+380995038736',
    'phoneTo' => '+380964894718',
    'text' => 'Hello'
]);

$request = new Request();
$request->setPaymentId('pay_online_1');
$request->setProperties($properties);
$client->send($request);