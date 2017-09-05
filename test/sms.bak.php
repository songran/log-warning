<?php
require_once __DIR__.'/../libs/Thrift.php';
 
 
$client = new Thrift(array('115.231.106.226:29090',));
$client->setServer('go.micro.srv.sms');

//18701343768
//18660126860
$responseObj = $client->setMethod('Sms.SendSMS')
    ->setParams([
        "type"      => 1,
        "phone"     => '18660126860',
        "tplId"     => '3',
        "tplParams" => json_encode(array('测试测试')) ,
 
    ])
    ->read();

//print_r($responseObj);
// 获取code
// 0-成功
// 400-错误的请求
// 401-未授权
// 403-禁止访问
// 404-服务或者方法不存在
// 500-内部错误
var_dump($responseObj->getCode());
// 获取返回结果
var_dump($responseObj->getData());
