<?php


namespace Seeker\iXiGua\Core;
use Illuminate\Filesystem\Filesystem;
use Seeker\iXiGua\Core\mongoDbClient;

class Downloader
{
    private $mongoDbDatabase = 'vod';
    private $mongodbCollect = 'infos';
    public function download(){

        $filesystem = new Filesystem();
        while ($info = mongoDbClient::find( $this->mongoDbDatabase, $this->mongodbCollect , ['status'=>1] )){
            $redis = new RedisClient();
            $redisInstance=  $redis->instance();
            if($redisInstance->get($info['videoId'])){
                echo "有其他进程在下载 {$info['videoTitle']} \r\n";
                mongoDbClient::updateDownloaded( $this->mongoDbDatabase , $this->mongodbCollect , $info['videoId'] ,3 );
                continue;
            }
            $lock = $redisInstance->setnx( $info['videoId'] ,$info['videoId'] );
            if( $lock ){
                try{
                    mongoDbClient::updateDownloaded( $this->mongoDbDatabase , $this->mongodbCollect , $info['videoId'] ,3 );
                    $downloadUrls = iterator_to_array($info['download']);
                    $fileName = $info['videoTitle'];
                    $fileSavePathName= storage_path( 'app/public/videos/'. $fileName.'.mp4' ) ;
                    if(file_exists($fileSavePathName)){
                        echo "文件已存在:{$fileName} \r\n";
                        mongoDbClient::updateDownloaded( $this->mongoDbDatabase , $this->mongodbCollect , $info['videoId'] );
                        continue;
                    }
                    echo "开始下载{$fileName} \r\n";
                    $videoContent = file_get_contents($downloadUrls[0]);
                    $filesystem->put($fileSavePathName, $videoContent);
                    mongoDbClient::updateDownloaded( $this->mongoDbDatabase , $this->mongodbCollect , $info['videoId'] );
                    $redisInstance->del( $info['videoId'] );
                    echo "下载完成\r\n";
                }catch (\Exception $exception){
                    echo "异常：{$exception->getMessage()} \r\n";
                }
            }

        }
        echo "任务全部下载完成 \r\n";
    }
}
