<?php
require __DIR__ . '/../libs/Kafka.php';
  
$kafkaMod    = new Kafka();
$kafkaMod->isConnect(); //监测kafka 是否连接
// print_r($kafkaMod);
// exit;
//执行消费部分
$count = 0; 
$arr   = array();
while(true) {   
    $msg = $kafkaMod->consumer->consume(1000);
     
    echo $msg->payload."\n";
    $count ++  ; 
}
 