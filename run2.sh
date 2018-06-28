#!/bin/sh
#user:songran
#des: daemon
#  nohup  ./run.sh > /dev/null
#  ./run.sh &
 
consumer="/Users/songran/GitHub/local/log-warning/consumer.php"
phpfile="/usr/local/php7/bin/php"

while true  
do  	 
	sn=`ps -ef | grep $consumer | grep -v grep|awk '{print $2}'`
    if [ "${sn}" = "" ] 
    then
         nohup $phpfile $consumer &
    fi
    ps -ef|grep $consumer |awk '{print $2}'|xargs kill -HUP
    sleep 5
done  