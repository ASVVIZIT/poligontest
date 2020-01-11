<?php

namespace App\Console;
use Carbon\Carbon;
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
        'App\Console\Commands\StartSerializingStatistics',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

         $schedule->command('statist:run')
                  ->cron("*/1 * * * *");
         $schedule->command('queue:work')
                  ->cron("*/1 * * * *");
        // $schedule->job(new \Rinvex\Statistics\Jobs\CrunchStatistics())
        //          ->everyMinute( Carbon::now()->addMinutes(1) )->cron("*/1 * * * *");

       // $schedule->job(\App\Jobs\SendMessage::withChain([
       //     new \Rinvex\Statistics\Jobs\CrunchStatistics(),
       // ])->dispatch("Запуск Задания сбора статистики")->delay(now()->addMinute(1)));
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
