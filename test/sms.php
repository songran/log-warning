<?php
include_once __DIR__."/../libs/Sms.php"; 
$smsMod      = new Sms();

//所有发送
 $smsMod->sendAllMsg('测试测试测试6666');



// //单个发送
// $tel = 18660126860;
// $msg = 'hello word xxx';

// $res = $smsMod->sendOneMsg($tel,$msg);
// var_dump($res);

 
 
