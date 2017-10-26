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
        Commands\CreateCheckingTasks::class,
        Commands\AddNewFilms::class,
        Commands\UpdateFilmHistoryValues::class,
        Commands\PublishOnSocial::class,
        Commands\ChangeThumbs::class,
        Commands\CheckNewThumbs::class,
        Commands\SendEmailNotifications::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('create:checking_tasks')
//            ->dailyAt('03:00');
//
//        $schedule->command('add:new_films')
//            ->dailyAt('00:00');
//
//        $schedule->command('publish:social')
//            ->dailyAt('23:00');
//
//        $schedule->command('check:thumbs')
//            ->hourly();
//
//        $schedule->command('send:semails')
//            ->dailyAt('23:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
