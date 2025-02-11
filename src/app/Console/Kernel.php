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
        Commands\SitemapGenerate::class,
        Commands\BotTimetableUpdates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sitemap:generate')
            ->hourly()
            ->onOneServer()
            ->runInBackground();
        $schedule->command('bot:timetable-updates')
            ->everyMinute()
            ->onOneServer()
            ->runInBackground();
    }
}
