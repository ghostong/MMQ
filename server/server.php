<?php
error_reporting(E_ALL ^ E_NOTICE);
define ( '__DS__' , DIRECTORY_SEPARATOR );
define ( '__RD__' , dirname (__FILE__) );
$LibCfgFile = __RD__.__DS__.'lib'.__DS__.'lib.config.php';

if ( is_file ( $LibCfgFile) ) {
    require ( $LibCfgFile );
}else{
    echo 'File '.$LibCfgFile.' not exist ! !'.PHP_EOL;
    exit;
}

if ( __ARGV__ == 'start' ) {
    foreach ($cfg['host'] as $val ) {
        $host = $val['host'];
        $port = $val['port'];
        MqServiceCreate( $host , $port );
    }
} elseif ( __ARGV__ == 'stop' ){
    foreach ($cfg['host'] as $val ) {
        $query['act'] = __STOP__;
        MqClientQuery ( $val , $query );
    }
    $PidArr = MqServiceFile2Pid();
    if ( $PidArr ) {
        foreach ( $PidArr as $val ) {
            posix_kill ( $val , 9);
        }
    }else{
        echo "Not search process !".PHP_EOL;
    }
    MqServiceCleanPidFile();
} elseif ( __ARGV__ == 'status' ) {
    $OutPut = '';
    foreach ($cfg['host'] as $val ) {
        $query['act'] = __STATUS__;
        $OutPut .= MqClientQuery ( $val , $query );
    }
    if ( $OutPut ) {
        echo $OutPut;
    } else {
        echo 'Service Not Fount !'.PHP_EOL;
    }

} elseif ( __ARGV__ == 'config' ) {
    print_r($cfg);

} else {
    echo '* Usage: '.__SCRIPT__.' {start|stop|status|config}'.PHP_EOL;

}
