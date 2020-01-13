<?php


namespace Seeker\iXiGua\Core;

use Predis\Client;

class RedisClient
{
    public function instance(){
        return new Client([
            'scheme' => 'tcp',
            'host'   => '0.0.0.0',
            'port'   => 6379,
        ]);
    }
}
