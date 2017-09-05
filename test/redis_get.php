<?php

include_once(__DIR__."/../libs/Redisclient.php");

$redis = new Redisclient();

$res = $redis->get('log-master');

if($res){
	echo 33333333;
}