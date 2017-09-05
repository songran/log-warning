<?php
function myLoader($class){
    $class = str_replace('\\','/',$class);
    require __DIR__ . '/libs/' . $class . '.php';
}
spl_autoload_register('myLoader');

$redisMod    = new Redisclient();
$smsMod      = new Sms();  
$kafkaMod    = new Kafka();

$kafkaMod->isConnect(); //监测kafka 是否连接
//执行消费部分
$count = 0; 
$arr   = array();
while(true) {   
    $msg = $kafkaMod->consumer->consume(1000);
    if($msg->err == RD_KAFKA_RESP_ERR_NO_ERROR && $msg->payload != null) { 
        $warning  =  json_decode($msg->payload, true); 
        if($warning != '') {
            $arr[md5($warning['content'])]  =  $warning;
        }     
    }
    if($count % 10 == 0) {
        $arrCount = count($arr);
        echo $arrCount."\n";
        if($arrCount < $kafkaMod->kafkaConf['maxNum'] && $arrCount >0) {           
             //错误少的时候 循环发送
             foreach ($arr as $v) { 
                sendMsg($v['id'], $v['content']); 
             }
        }elseif($arrCount > $kafkaMod->kafkaConf['maxNum']) {
             //消息抖动 出现批量错误时候 发一条消息  等三分钟后再发  
             sendMsg(1, '瞬间批量错误');
        }   
        $arr = array();
    }
    echo $msg->payload."\n";
    $count ++  ; 
}
//发送消息
function  sendMsg($id='', $content='') {
     global $redisMod;
     global $smsMod;
     global $kafkaMod;

     $resMsg      =  false;
     $msgInfo     =  $id.':'.$content;
     $contentMd5  =  md5($content);
     if(!$redisMod->get($contentMd5)) {
          $resMsg =  $smsMod->sendAllMsg($msgInfo);
          if($resMsg){
            $redisMod->set($contentMd5, 1, 0, 0, 180);
          }else{
            //再存储到队列里
            // $arr = array(
            //     'id'      =>$id,
            //     'content' =>$content
            // );
            // $msg = json_encode($arr);
            // $kafkaMod->product($msg); 
          }               
     }    
}