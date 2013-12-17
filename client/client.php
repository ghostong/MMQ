<?php
include (dirname ( dirname(__FILE__) ).'/server/class/class.MqSocket.php');

MqSocket::addServer('127.0.0.1','65501');
#MqSocket::addServer('127.0.0.1','65502');
#MqSocket::addServer('127.0.0.1','65503');

MqSocket::query (array('act' => 'test','data'=>'myQueryTest'));

var_dump ( MqSocket::getReturn() );
#var_dump ( MqSocket::getError() );
