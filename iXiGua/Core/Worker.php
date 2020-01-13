<?php


namespace Seeker\iXiGua\Core;
use Illuminate\Support\Facades\Log;
use Seeker\iXiGua\Core\mongoDbClient;

class Worker
{
    private $mongoDbDatabase = 'vod';
    private $mongodbCollect = 'infos';
    public function start( $sec ,$url){
        $getChannel = new GetChannelList();
        while ($sec >0){
            $content =$getChannel->getChannelContent( $url );
            $this->writeContent($content);
            $sec--;
        }
    }

    private function writeContent( $apiContent  ){
        try{
            $apiContent = array_map(function ($item ){
                $item['status'] = 0;
                $item['videoPageUrl'] = 'https://m.ixigua.com/i'.$item['videoId'];
                unset($item['tag'] , $item['playNum'] , $item['timeText'] , $item['blackText'] , $item['commentNum']);
                return $item;
            } , $apiContent['data']['channelFeed']['Data']);
            if(!$apiContent){
                return false;
            }
            return mongoDbClient::batchInsert($this->mongoDbDatabase , $this->mongodbCollect  , $apiContent );
        }catch (\Exception $exception){
            Log::info('writeContent_Exception', ['msg'=>$exception->getMessage() ] );
            return false;
        }
    }
}
