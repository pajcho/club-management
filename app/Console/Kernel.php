<?php namespace App\Console;

use App\Console\Commands\AppInstall;
use App\Console\Commands\ClearHistory;
use App\Console\Commands\Inspire;
use App\Console\Commands\UpdateMembersGroupDateHistory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Inspire::class,
		AppInstall::class,
		ClearHistory::class,
		UpdateMembersGroupDateHistory::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		$schedule->exec('php artisan backup:run --only-db')->twiceDaily(5, 17);
		$schedule->command('app:clear-history --months=2')->weekly()->sundays()->at('04:00');
	}

}
