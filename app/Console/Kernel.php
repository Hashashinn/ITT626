<?php

namespace App\Console;

use App\Console\Commands\LogoutNotification;
use App\Console\Commands\ResetNotifyLogout;
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
        ResetNotifyLogout::class,
        LogoutNotification::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('sanctum:prune-expired --hours=24')->daily();
        // send email to notify logout
        $schedule->command('logoutNotify:send')->everyMinute()->weekdays();
        // reset notify to false in order to send notification on the next day
        $schedule->command('resetNotifyLogout:send')->weekdays()->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}