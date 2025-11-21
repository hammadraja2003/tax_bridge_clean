<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run the expire packages command daily
        $schedule->command('packages:expire')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load your commands
        $this->load(__DIR__ . '/Commands');

        // You can also load routes/console.php if needed
        require base_path('routes/console.php');
    }
}
