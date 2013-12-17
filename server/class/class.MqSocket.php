<?php

class MqSocket {

    public static $hostList = array();
    private static $connHost = '';
    private static $connPort = '';
    private static $objSocket = '';
    private static $readResult = '';
    private static $error = '';
    private static $Config = array (
        'read_len' => '256',
    );

    static function addServer ( $host , $port ){
        self::$hostList[] = array ( 'host' =>$host , 'port'=>$port );
    }

    static function query( $queryArr ){
        try {
			self::checkArgv($queryArr);
            self::socketQueryHost();
            self::socketCreate();
            self::socketConnect();
			$queryStr = serialize ($queryArr);
            #self::socketWrite( $queryStr );
			self::socketWrite( 'uueouhneohu' );
            self::socketRead();
            self::socketClose();

        }catch (Exception $e){
            $emsg = $e -> getMessage();
            $ErrTips = self::getErrTips();

            if ( $ErrTips[$emsg] ){
                self::$error = $emsg.' '.$ErrTips[$emsg];

            }else{
                $errno = socket_last_error( self::$objSocket );
                $errstr = socket_strerror ( $errno );
                self::$error = $errno.' '.$errstr;

            }

        }

    }

	static function checkArgv(){
		if( empty($queryArr['act']) ) {
			throw new Exception ('#5');
		}	
	}
	
    static function getReturn(){
        return self::$readResult;

    }

    static function getError(){
        return self::$error;

    }

    static private function socketQueryHost(){
        $hostCount = count ( self::$hostList );

        if ( $hostCount > 1 ) {
            $retHost = self::$hostList[rand(0,$hostCount-1)];

        } else {
            $retHost = self::$hostList[0];

        }

        if ( isset($retHost['host']) && isset($retHost['port']) ){
            self::$connHost = $retHost['host'];
            self::$connPort = $retHost['port'];

        }else{
            throw new Exception ('#1');

        }

    }

    static private function socketCreate(){
        self::$objSocket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ( !is_resource( self::$objSocket ) ) {
            throw new Exception ('#2');

        }

    }

    static private function socketConnect(){
        $conRes = @socket_connect( self::$objSocket , self::$connHost , self::$connPort );

        if ( $conRes != true){
            throw new Exception ('#3');

        }

    }

    static private function socketWrite( $contents ){
        if ( $contents ) {
			var_dump (self::$objSocket ,$contents ,strlen( $contents ));
            $result = @socket_write( self::$objSocket ,$contents ,strlen( $contents ) );

            if ( !$result ){
                throw new Exception ('#5');

            }

        }else{
            throw new Exception ('#4');

        }

    }

    static private function socketRead( ){
        self::$readResult = @socket_read( self::$objSocket , self::$Config['read_len'] );

    }

    static function socketClose(){
        socket_close( self::$objSocket );

    }

    static function setConfig( $cfg , $val){
        self::$Config[$cfg] = $val;

    }

    static function getConfig(){
        return self::$Config;

    }

    static function getErrTips(){
        return array (
            '#1' => 'Host Error',
            '#2' => 'Socket Create Error',
            '#3' => 'Socket Connect Error',
            '#4' => 'Socket Write Contents Empyt',
            '#5' => 'Socket Write Error',
			'#6' => 'Query Param Error',
        );

    }

}
