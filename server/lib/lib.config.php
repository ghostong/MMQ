<?php

//Check Ext
if ( !function_exists('pcntl_fork') ){
    echo 'PHP extension pcntl not exist'.PHP_EOL;
    exit;
}

//Check Ext
if ( !function_exists('socket_create') ){
    echo 'PHP extension socket not exist'.PHP_EOL;
    exit;
}

//User Config
$UserCfgFile = __RD__.__DS__.'user'.__DS__.'user.cfg.php';
if ( is_file ( $UserCfgFile ) ) {
    require ( $UserCfgFile );
}else{
    echo 'File '.$UserCfgFile.' not exist ! !'.PHP_EOL;
    exit;
}

//Server Core
$LibSerFile =  __RD__.__DS__.'lib'.__DS__.'lib.server.php' ;
if ( is_file ( $LibSerFile ) ) {
    require ( $LibSerFile );
}else{
    echo 'File '.$LibSerFile.' not exist ! !'.PHP_EOL;
    exit;
}

//Client Class File
$ClassCliFile = __RD__.__DS__.'class'.__DS__.'class.MqSocket.php';
if ( is_file ( $ClassCliFile ) ) {
    require ( $ClassCliFile );
}else{
    echo 'File '.$ClassCliFile.' not exist ! !'.PHP_EOL;
    exit;
}

//Client Core
$LibCliFile = __RD__.__DS__.'lib'.__DS__.'lib.client.php';
if ( is_file ( $LibCliFile ) ) {
    require ( $LibCliFile );
}else{
    echo 'File '.$LibCliFile.' not exist ! !'.PHP_EOL;
    exit;
}

//Daemon Process
$DaeProcFile =  __RD__.__DS__.'lib'.__DS__.'lib.daemon.php';
if ( !is_file ( $DaeProcFile ) ) {
    echo 'File '.$DaeProcFile.' not exist ! !'.PHP_EOL;
    exit;
}


//Script
define ( '__SCRIPT__' , strtolower ( $_SERVER["argv"][0] ) );

//Script Argv
define ( '__ARGV__' , strtolower ( $_SERVER["argv"][1] ) );

//Query Data Mark
define ( '__QUERY__' , '_|_go_go_go_|_' );

//Stop Service Mark
define ( '__STOP__' , '_|_stop_stop_|_' );

//Show Status Mark
define ( '__STATUS__' , '_|_sta_tus_|_' );

//PHP Path
isset ( $cfg['php_path'] ) or $cfg['php_path'] = $_SERVER["_"];

//Max Cache Num
isset ( $cfg['max_cache'] ) or $cfg['max_cache'] = 1000;

//Pid Save File
isset ( $cfg['pid_file'] ) or $cfg['pid_file'] = '/tmp/phpmq.pid';

//Max free Time
isset ( $cfg['free_time'] ) or $cfg['free_time'] = 600;

//Max receive length
isset ( $cfg['receive_len']) or $cfg['receive_len'] = 1024;

//Define Shell Path
isset ( $cfg['run_shell'] ) or $cfg['run_shell'] = __RD__.__DS__.'ext'.__DS__.'ext.shell.php';

//Server Start Time
$GLOBALS['server_start_time'] = date('Y-m-d H:i:s');

//Last Query Time
$GLOBALS['last_query_time'] = 'None';

//Query Times
$GLOBALS['query_times'] = 0;

//Query Count Data
$GLOBALS['query_count'] = 0;
