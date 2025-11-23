<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Наши пользовательские команды
     */
    protected $commands = [
        Commands\SyncApiData::class,
    ];

    /**
     * Планировщик команд (cron)
     */
    protected function schedule(Schedule $schedule)
    {
        // Обновлять данные каждый день в 23:00
        // $schedule->command('api:sync')->dailyAt('23:00');
    }

    /**
     * Регистрация консольных команд
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
