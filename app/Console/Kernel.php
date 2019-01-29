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
        Commands\UpdateDb::class,

        Commands\UpdateEid::class,
        Commands\UpdateVl::class,

        Commands\EidNation::class,
        Commands\EidCounty::class,
        Commands\EidSubcounty::class,
        Commands\EidFacility::class,
        Commands\EidLab::class,
        Commands\EidPoc::class,
        Commands\EidPartner::class,

        Commands\VlNation::class,
        Commands\VlCounty::class,
        Commands\VlSubcounty::class,
        Commands\VlFacility::class,
        Commands\VlLab::class,
        Commands\VlPartner::class,
        Commands\VlLablog::class,
        Commands\VlSamples::class,
        Commands\VlSuppression::class,

        Commands\EidPatients::class,
        Commands\VlPatients::class,

        Commands\EidTat::class,
        Commands\VlTat::class,

        Commands\EidConfirmatory::class,
        Commands\VlConfirmatory::class,
        
        Commands\InsertEid::class,
        Commands\InsertVl::class,

        Commands\SendReport::class,
        Commands\TestMail::class,
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

        $filePath = public_path('logs.txt');

        // $schedule->command('update:all')->dailyAt('19:00')->withoutOverlapping()->appendOutputTo($filePath);
        $schedule->command('update:all')->dailyAt('19:00')->withoutOverlapping()->emailOutputTo('joelkith@gmail.com');
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
