<?php
require_once __DIR__.'/Thrift.php';
 
class Sms{


	private $telArr;
	private $rpcConf;

	public function __construct()
	{	
		$this->telArr   = include_once __DIR__.'/../conf/tel.conf.php';
		$this->rpcConf  = include_once __DIR__.'/../conf/rpc.conf.php'; 	
	}

	//批量发送 多个 消息
	public function sendAllMsg($msg)
	{
		$info = '';
		$res  = false;	
		foreach($this->telArr as $tel)
		{
			$res =  $this->sendOneMsg($tel,$msg);

			if($res){
				$res = true;
				$info = '成功 '.$tel.' '.$msg;
				$this->writeLog($info);
			}else{
				$resH = $this->sendOneMsg($tel,$msg);
				if($resH){
					$res  = true; 
					$info = '成功 '.$tel.' '.$msg;	
				}else{
					$info = '失败 '.$tel.' '.$msg;
				}
				$this->writeLog($info);
			}
		}
		return  $res;
	}


	//发送单个消息
	public function  sendOneMsg($tel='',$msg='')
	{
		$res = false;
		try { 
			$client = new Thrift($this->rpcConf);
			$resObj = $client->setServer('go.micro.srv.sms')
					 	     ->setMethod('Sms.SendSMS')
						     ->setParams([
					        "type"      => 1,
					        "phone"     => (string)$tel,
					        "tplId"     => '3',
					        "tplParams" => json_encode(array($msg)) ,
			    ])->read();			
			$resDate = $resObj->getData();
			//print_r($resDate);
			if($resDate && $resDate['code']==0){
				$res = true;
			}else{
				$res = false;
			}
		} catch (Exception $e) { 					 		  
			//print $e->getMessage();
			$res = false;
		}	
		return $res;
	}
	//记录日志
	public function writeLog($msg ='' )
	{
		$info = date('Y/m/d H:i:s',time()).' '.$msg."\n";
		file_put_contents(__DIR__."/../logs/sms.txt",$info,FILE_APPEND ); 
	}

 

}