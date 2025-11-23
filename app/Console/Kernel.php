<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Bizning custom commandlarimiz
     */
    protected $commands = [
        Commands\SyncApiData::class,
    ];

    /**
     * Commandlarni jadvallashtirish (cron)
     */
    protected function schedule(Schedule $schedule)
    {
        // Har kuni kechqurun soat 23:00 da ma'lumotlarni yangilab turish
        // $schedule->command('api:sync')->dailyAt('23:00');
    }

    /**
     * Console commandlarini ro'yxatga olish
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
