<?php

namespace App\Console\Commands;

use App\Models\ActivePower;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupActivePower extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:active-power';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Old Active Power data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ActivePower::whereDate('created_at', '<', Carbon::today()->subMonths(3)->toDateString())->delete();
    }
}
