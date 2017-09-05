<?php

include_once(__DIR__."/../libs/Redisclient.php");

$redis = new Redisclient();

$res  = $redis->set('log-master',11111,0,0,$time=10);

 
var_dump($res);