<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('app:task-approaching-duedate-auto')->everyMinute();
        $schedule->command('app:task-past-duedate-auto')->twiceDaily(9, 15);
        // $schedule->command('app:task-past-duedate-auto')->everyMinute();

        // $schedule->command('queue:restart')
        //     ->everyFiveMinutes();

        // $schedule->command('queue:work --daemon')
        //     ->everyMinute()
        //     ->withoutOverlapping();
        $schedule->command('queue:work --stop-when-empty')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
