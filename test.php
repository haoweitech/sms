<?php

require __DIR__ . '/vendor/autoload.php';

use Hwtech\Sms\Client;
use Hwtech\Sms\Request;

$client = new Client();
$client->setAppId('hw_100001');
$client->setSecretKey('123456');

$request = new Request();
$request->setMethod('sms.message.send');
$request->setBizContent([
    'mobile' => ['15056009753'],
    'template_id' => 'ST_2019043000000001',
    'type' => 1,
    'sign' => '好为科技',
    'send_time' => '',
    'params' => [
        'code' => 1569
    ]
]);
list($ret, $errno, $errstr, $et) = $client->execute($request);
var_dump($ret, $errno, $errstr, $et);
