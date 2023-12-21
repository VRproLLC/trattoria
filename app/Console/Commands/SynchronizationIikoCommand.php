<?php

namespace App\Console\Commands;

use App\Services\IikoService;
use Illuminate\Console\Command;

class SynchronizationIikoCommand extends Command
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
        $sync = new IikoService();
        $sync->sync();

        return 1;
    }
}
