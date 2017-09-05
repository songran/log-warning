#!/bin/sh
#Filename: run.sh
#  nohup  ./run.sh > /dev/null
 
consumer="/app/www/log-warning/consumer.php"
phpfile="/app/pluops/php71/bin/php"

while true  
do   
   ps -ef|grep $consumer |awk '{print $2}'|xargs kill -9
   $phpfile $consumer 
   sleep 3600  
done  