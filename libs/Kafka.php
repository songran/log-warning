<?php
class Kafka{

	public  $kafkaConf;
	public  $topicConf;
	public  $consumer;

	public function __construct(){
		$this->kafkaConf   = include_once __DIR__.'/../conf/kafka.conf.php';
		$this->getConsumer();
	}

	
	public function  getConsumer()
	{
		$conf = new RdKafka\Conf();
		$conf->set('group.id', $this->kafkaConf['group']);
		$conf->set('metadata.broker.list', $this->kafkaConf['brokerList']);
		$topicConf = new RdKafka\TopicConf();
		$topicConf->set('auto.offset.reset', 'smallest');
		$conf->setDefaultTopicConf($topicConf);
		$consumer = new RdKafka\KafkaConsumer($conf);
		$consumer->subscribe([$this->kafkaConf['topic']]);

		$this->topicConf = $topicConf;
		$this->consumer = $consumer;
		return $consumer;
	}


	//查看kafka 是否连接正常 
	public function isConnect(){
		try{
		    $topic = $this->consumer->newTopic($this->kafkaConf['topic'],$this->topicConf);
		    $this->consumer->getMetadata(false, $topic, 1000) ;
		}catch(\Exception $e){   
		    $info = date('Y/m/d H:i:s',time()).' '.$e->getMessage()."\n";
		    file_put_contents(__DIR__."/../logs/kafka.txt",$info,FILE_APPEND );
		}
	}

	//生产者 
	public  function  product($msg)
	{
		$rk = new \RdKafka\Producer();
		$rk->setLogLevel(LOG_DEBUG);
		$rk->addBrokers($this->kafkaConf['brokerList']);
		$topic = $rk->newTopic($this->kafkaConf['topic']); 
		$topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
	}
	   
	

}