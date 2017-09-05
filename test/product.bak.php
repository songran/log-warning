<?php
$rk = new \RdKafka\Producer();
$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers("120.25.98.72:9092");

$topic = $rk->newTopic("test");

$arr = array(
	'id'     =>'11111111',
	'content'=> (string)$argv[1], 
);
$topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($arr));
