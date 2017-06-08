<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    Commands\MultithreadingRequest::class,
    Commands\JokeSpider::class,
    Commands\ImageSpider::class,
    Commands\Test::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('test')->everyMinute();
    $schedule->command('spider:joke')
      ->daily();
    $schedule->command('spider:image')
      ->daily();
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
