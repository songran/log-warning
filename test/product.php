<?php
 /*
 *	生产者
 *  运行方式 php  product.php 预警信息
 */
require __DIR__ . '/../libs/Kafka.php';
  
$kafkaMod    = new Kafka();
 

$arr = array(
	'id'     =>'11111111',
	'content'=> (string)$argv[1], 
);
$kafkaMod->product(json_encode($arr));  
