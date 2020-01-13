<?php

namespace Seeker\iXiGua\Console\Commands;

use Illuminate\Console\Command;
use Seeker\iXiGua\Core\Worker;

class SeekerWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeker:worker {channel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ixigua seeker';


    /**
     * 系统版本
     */
    const VERSION = "1.0";

    public function handle(){

        $channel = $this->argument('channel');
        $url = "https://www.ixigua.com/api/feedv2/feedById?count=30&channelId=";
        if(  $channel == 'music' ){
            $url .= 61887739368;
        }elseif ($channel == 'ertong'){
            $url .= 6141508395;
        }else{
            $this->error("未知频道");
            return false;
        }
        $worker = new Worker();
        $worker->start( 30, $url  );
    }

}
