<?php

namespace Seeker\iXiGua\Console\Commands;

use Illuminate\Console\Command;
use Seeker\iXiGua\Core\Consumer;
use Seeker\iXiGua\Core\Downloader;
use Seeker\iXiGua\Core\mongoDbClient;
use Seeker\iXiGua\Core\Worker;

class SeekerDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeker:download {workNum?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ixigua seeker download';

    private $workNum = 0;

    /**
     * 系统版本
     */
    const VERSION = "1.0";

    public function handle(){
        $workNum =  $this->argument('workNum') ;
        $this->workNum = $workNum ? $workNum : 1;
        $workers=[];
        $while = $this->workNum;
        while ( $while>0 ){
            $process = new \swoole_process(function(\swoole_process $work){
                $info = new Downloader();
                $info->download();
            });
            $pid = $process->start();
            $workers[$pid] = $process;
            $while--;
        }

        \swoole_process::wait();


    }
}
