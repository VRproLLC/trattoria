<?php

namespace App\Console\Commands;

use App\Http\Controllers\SyncController as HomeSyncController;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use OneSignal\Config as OneSignalConfig;
use OneSignal\OneSignal;
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

class SynchronizationNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trattoria:synchronization';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация айки';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $sync = new HomeSyncController();
        $sync->sync();

        return 1;
    }
}
