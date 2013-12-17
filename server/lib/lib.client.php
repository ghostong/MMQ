<?php

function MqClientQuery ( $host , $query , $read = array() ) {

    MqSocket::addServer($host['host'],$host['port']);
    MqSocket::query ($query);

    return MqSocket::getReturn();

}
