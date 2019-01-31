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
        Commands\GsmExtract::class,
        Commands\VolteExtract::class,
        Commands\LteExtract::class,
        Commands\NbiExtract::class,
        Commands\SLteExtract::class,
        Commands\SGsmExtract::class,
        Commands\BLNbiExtract::class,
        Commands\BLGsmExtract::class,
        Commands\BLTddExtract::class,
        Commands\BLFddExtract::class,
        Commands\GsmDay::class,
        Commands\NbiotDay::class,
        Commands\VolteDay::class,
        Commands\LteDay::class,


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        date_default_timezone_set("PRC");
        $schedule->command('Gsm:backup')->cron("20 * * * *");
        $schedule->command('Volte:backup')->cron("22 * * * *");
        $schedule->command('Lte:backup')->cron("22 * * * *");
        $schedule->command('Nbi:backup')->cron("22 * * * *");
        //备份B_S_FDD/TDD/NBIOT数据
        $schedule->command('SLte:backup')->cron("0 0 * * *");
        //B_S_GSM数据备份
        $schedule->command('SGsm:backup')->cron("20 0 * * *");
        $schedule->command('BLNbi:backup')->cron("20 0 * * *");
        $schedule->command('BLGsm:backup')->cron("20 * * * *");
        $schedule->command('BLTdd:backup')->cron("20 1 * * *");
        $schedule->command('BLFdd:backup')->cron("20 1 * * *");
        //指标查询备份天级别数据
        $schedule->command('GsmDay:backup')->dailyAt("02:00");//GSM天级别
        $schedule->command('NbiotDay:backup')->dailyAt("02:00");//Nbiot天级别
        $schedule->command('VolteDay:backup')->dailyAt("02:00");//Volte天级别
        $schedule->command('LteDay:backup')->dailyAt("02:00");//Lte天级别

        // $schedule->command('VolteExtract:backup')->cron("20 * * * *");
        // $schedule->command('inspire')
        //          ->hourly();
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
