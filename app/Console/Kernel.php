<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	protected function commands(): void
	{
		$this->load(__DIR__.'/Commands');
	}
	
	protected function schedule(Schedule $schedule): void
	{
		if ($expression = config('database.reset_cron')) {
			$schedule
				->command(FreshCommand::class, ['--seed', '--force'])
				->cron($expression);
		}
	}
}
