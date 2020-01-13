<?php

namespace Seeker\iXiGua\Core;

use GuzzleHttp\Client;
class Http
{
    private static $instance = null;

    private static function getInstance(){
        if( self::$instance == null){
            self::$instance = new Client();
        }
        return self::$instance;
    }


    public static function get( $url, $options=[] ){

        return self::getInstance()->get( $url,$options );
    }
}
