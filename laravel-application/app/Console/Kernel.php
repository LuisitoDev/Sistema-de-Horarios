<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\ScheduledObjects\CambioDeRotacion;
use App\Console\ScheduledObjects\CierreDeHoras;
use Log;

class Kernel extends ConsoleKernel
{
    private $debug = true;
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if(!env('DEBUGGER')){
            $schedule->call(new CierreDeHoras)
                ->timezone('America/Mexico_City')
                ->dailyAt('20:30')
                ->weekdays();

           $schedule->call(function(){
                Log::info('Task scheduling listening for pending tasks...');
           })->everyMinute();
        }

        if(env('DEBUGGER')) {
            $schedule->call(new CierreDeHoras)
                ->timezone('America/Mexico_City')
                ->everyMinute();
        }
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
