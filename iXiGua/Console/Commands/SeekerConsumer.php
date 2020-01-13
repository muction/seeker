<?php

namespace Seeker\iXiGua\Console\Commands;

use Illuminate\Console\Command;
use Seeker\iXiGua\Core\Consumer;
use Seeker\iXiGua\Core\Worker;

class SeekerConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeker:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ixigua seeker consumer';


    /**
     * 系统版本
     */
    const VERSION = "1.0";

    public function handle(){

        //手动启动selenium

        //开始消费
        $consumer= new Consumer();
        $consumer->start();
    }

}
