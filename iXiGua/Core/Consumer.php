<?php


namespace Seeker\iXiGua\Core;

use Seeker\iXiGua\Core\mongoDbClient;
use Seeker\iXiGua\Selenium\iXiGuaSelenium;

class Consumer
{
    private $mongoDbDatabase = 'vod';
    private $mongodbCollect = 'infos';

    public function start(){
        while ( $content =  mongoDbClient::find( $this->mongoDbDatabase, $this->mongodbCollect , ['status'=>0 ] )){
            $selenium = new iXiGuaSelenium();
            $source = $selenium->get( $content['videoPageUrl'] );
            mongoDbClient::updateDownloadInfo( $this->mongoDbDatabase, $this->mongodbCollect , $content['videoId'] , $source );
        }
    }
}
