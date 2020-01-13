<?php


namespace Seeker\iXiGua\Core;
use MongoDB\Client;

class mongoDbClient
{
    private static $instance = null;
    private static function getInstance( $dataBase, $collection ){
        if( !self::$instance ){
            self::$instance = (new Client)->selectDatabase($dataBase)->selectCollection($collection) ;
        }
        return self::$instance;
    }

    /**
     * 查找一条
     * @param $dataBase
     * @param $collection
     * @param array $filter
     * @return mixed
     */
    public static function find($dataBase, $collection , $filter=[] ){

        $instance= self::getInstance($dataBase, $collection)->findOne( $filter );
        return $instance ? iterator_to_array( $instance ): [];
    }

    /**
     * 更新一条
     * @param $dataBase
     * @param $collection
     * @param $id
     * @param $downloadUrl
     * @return \MongoDB\UpdateResult
     */
    public static function updateDownloadInfo($dataBase, $collection, $id, $downloadUrl ){

        return self::getInstance($dataBase, $collection)->updateOne(['videoId'=>$id] , ['$set'=>  ['download'=>$downloadUrl ,'status'=>1]]);
    }

    /**
     * @param $dataBase
     * @param $collection
     * @param array $filter
     * @return int
     */
    public static function count($dataBase, $collection, $filter=[] ){
        return self::getInstance($dataBase, $collection)->count( $filter );
    }

    /**
     * 批量更新
     * @param $dataBase
     * @param $collection
     * @param array $filter
     * @param array $update
     * @return \MongoDB\UpdateResult
     */
    public static function updates($dataBase, $collection, array $filter , $update=[] ){
        return self::getInstance($dataBase, $collection)->updateMany($filter , $update );
    }

    /**
     * 批量写入
     * @param $dataBase
     * @param $collection
     * @param array $data
     * @return \MongoDB\InsertManyResult
     */
    public static function batchInsert($dataBase, $collection, array $data){
        return self::getInstance($dataBase, $collection)->insertMany( $data );
    }

    /**
     * 下载完成
     * @param $dataBase
     * @param $collection
     * @param $id
     * @param int $status
     * @return \MongoDB\UpdateResult
     */
    public static function updateDownloaded($dataBase, $collection, $id ,$status=2){

        return self::getInstance($dataBase, $collection)->updateOne(['videoId'=>$id] , ['$set'=>  ['status'=>$status]]);
    }

}
