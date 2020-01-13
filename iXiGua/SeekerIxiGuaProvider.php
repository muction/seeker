<?php

namespace Seeker\iXiGua;

use Illuminate\Support\ServiceProvider;
class SeekerIxiGuaProvider extends ServiceProvider
{

    private $commands = [
        Console\Commands\SeekerWorker::class ,
        Console\Commands\SeekerConsumer::class ,
        Console\Commands\SeekerDownload::class ,
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->runningInConsole()){
            $this->commands( $this->commands );
        }
    }

    /**

     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/ixigua.php' => config_path('ixigua.php'),
        ], 'seeker-ixigua');
    }

}
