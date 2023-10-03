<?php
$msisdn = '8801962424219';
$appname = 'VOC';
$raddr = '1234';
$message = 'test message';

echo "http://10.10.31.115/kannel/ivr_sms/service.php?msisdn=$msisdn&app=$appname&raddr=$raddr&message=$message";
?>