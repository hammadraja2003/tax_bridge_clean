<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BusinessPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExpirePackages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'packages:expire';

    /**
     * The console command description.
     */
    protected $description = 'Expire trial and normal business packages whose end date or trial end date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::now();

        // Expire normal packages
        $normalExpired = BusinessPackage::where('is_trial', false)
            ->where('is_active', true)
            ->where('end_date', '<', $today)
            ->update(['is_active' => false]);

        // Expire trial packages
        $trialExpired = BusinessPackage::where('is_trial', true)
            ->where('is_active', true)
            ->where('trial_end_date', '<', $today)
            ->update(['is_active' => false]);

              // Log the event
        Log::info("ExpirePackages command ran at ". now() . ". Normal packages expired: " . $normalExpired);
        Log::info("ExpirePackages command ran at ". now() . ". Trial packages expire " . $trialExpired);


        return 0;
    }

    //How to Run 
    // php artisan packages:expire

}
