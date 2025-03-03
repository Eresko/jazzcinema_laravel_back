<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:film_copy')
            ->dailyAt('01:22');
        $schedule->command('update:film_copy')
            ->dailyAt('09:05');
//        $schedule->command('update:film_copy')
//            ->everyFifteenMinutes();

        
        $schedule->command('export:schedule')
           ->everyMinute();
        $schedule->command('export:guest')
            //->dailyAt('19:05');
           ->everyThreeMinutes();
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
