<?php

function MqServiceCreate( $host , $port ) {
    if ($host && $port) {
        $pid = pcntl_fork();

        if ($pid == 0) {

            pcntl_signal( SIGCHLD , SIG_IGN);
            $socket=socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket".PHP_EOL);  
            $result=socket_bind($socket,$host,$port) or die("Could not bind to socket".PHP_EOL);
            $result=socket_listen($socket,3) or die("Could not set up socket listener".PHP_EOL);
            
            $i=0;
            $data = array();

            while (true) {
                $i ++;
                $spawn=socket_accept($socket) or die("Could not accept incoming connection".PHP_EOL);
                $input=socket_read($spawn,$GLOBALS['cfg']['receive_len']);
                $input=trim($input);
				$input=unserialize($input);

                if ($input) {

                    if ( $input['act'] == __STOP__ ) {
                        MqServiceRunShell($data);
                        socket_close($spawn);
                        socket_close($socket);
                        break;

                    } elseif ( $input['act'] == __QUERY__ ) {
                        MqServiceRunShell($data);
                        $data = array();
                        $i = 0;

                    } elseif ( $input['act'] == __STATUS__ ) {
                        $MyPid = getmypid();
                        $CountData = count($data);
                        $StartTime = $GLOBALS['server_start_time'];
                        $LastQuery = $GLOBALS['last_query_time'];
                        $QueryTimes = $GLOBALS['query_times'];
                        $QueryCount = $GLOBALS['query_count'];
                        $RetStr = 
                            'host : ['.$host.':'.$port.']'.PHP_EOL.
                            'process_id : '.$MyPid.PHP_EOL.
                            'date_count : '.$CountData.PHP_EOL.
                            'start_time : '.$StartTime.PHP_EOL.
                            'last_query : '.$LastQuery.PHP_EOL.
                            'query_times: '.$QueryTimes.PHP_EOL.
                            'query_count: '.$QueryCount.PHP_EOL.
                            '------------------------------'.PHP_EOL;
                        socket_write($spawn,$RetStr,strlen($RetStr)) or die("Could not write output".PHP_EOL);

                    } else {
                        socket_write($spawn,true,strlen(true)) or die("Could not write output".PHP_EOL);
                        $data[]=$input;
						echo 11111111111111111111111111;
						var_dump ($i , $GLOBALS['cfg']['max_cache'],$input);
                        if ($i >= $GLOBALS['cfg']['max_cache']){
                            MqServiceRunShell($data);
                            $data = array();
                            $i = 0;

                        }

                    }

                }

                socket_close($spawn);

            }

            socket_close($socket);

            exit;
        }

    }

}


function MqServiceRunShell($data){
    if (empty ($data)){
        return;
    }
var_dump ($data);
    $GLOBALS['last_query_time'] = date('Y-m-d H:i:s');
    $GLOBALS['query_times'] ++ ;
    $GLOBALS['query_count'] +=count($data) ;

    $spid = pcntl_fork();
    if ( $spid == 0 ) {
        include ( $GLOBALS['cfg']['run_shell'] );
        exit;
    }

}


function MqServicePid2File( $pid ){
    file_put_contents ( $GLOBALS['cfg']['pid_file'] , getmypid().PHP_EOL , FILE_APPEND );

}


function MqServiceFile2Pid( ){
    $pids = file_get_contents($GLOBALS['cfg']['pid_file']);
    $PidArr = explode ( PHP_EOL , $pids );
    $PidArr = array_filter ( $PidArr );
    if ($PidArr){
        return $PidArr;
    }else{
        return false;
    }

}


function MqServiceCleanPidFile(){
    file_put_contents ( $GLOBALS['cfg']['pid_file'] , '' );

}


function MqServiceDaemon(){
    $pid = pcntl_fork();
    if ($pid == 0) {
        MqServicePid2File( getmypid() );
        pcntl_signal( SIGCHLD , SIG_IGN);
        pcntl_exec( $GLOBALS['cfg']['php_path'] , array ( $GLOBALS['DaeProcFile'] ) );
    }

}
