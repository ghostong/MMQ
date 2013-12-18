<?php

error_reporting(E_ALL ^ E_NOTICE);
$_SERVER["argv"][2] = $_SERVER['ShellParam2'];
$_SERVER["argv"][3] = $_SERVER['ShellParam3'];

define ( '__DS__' , DIRECTORY_SEPARATOR );
define ( '__RD__' , dirname ( dirname (__FILE__) ) );
$LibCfgFile = __RD__.__DS__.'lib'.__DS__.'lib.config.php';

if ( is_file ( $LibCfgFile ) ) {
    require ( $LibCfgFile );
}else{
    echo 'File '.$LibCfgFile.' not exist ! !'.PHP_EOL;
    exit;
}

while (true) {
    sleep($GLOBALS['cfg']['free_time']);
    foreach ($GLOBALS['cfg']['host'] as $val) {
        $query['act'] = __QUERY__;
        MqClientQuery($val,$query,$read);

    }

}
