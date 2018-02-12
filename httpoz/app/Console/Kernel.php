<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        var_dump($_SERVER['argv']);
        // $schedule->command('inspire')
        //          ->hourly();
        //$file_log = 'storage' . DIRECTORY_SEPARATOR . 'salida.cron.balanzas' . DIRECTORY_SEPARATOR . 'log.txt';
        $file_log = 'salida.cron.balanzas' . DIRECTORY_SEPARATOR . 'log.txt';
        $path_log = storage_path($file_log);
        //print($path_log);
        $schedule->command('balanza:cargardatos', ["balanza1"])
                  //->exec('balanza:cargardatos', ["--balanza=1"])
                  //->everyThirtyMinutes()
                  ->everyMinute()
                  ->appendOutputTo($path_log);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
