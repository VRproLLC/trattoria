<?php

namespace App\Console\Commands;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CronWorkTimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbara:time-work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Перевод точек в сон или на оборот';

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
        $organization = Organization::where('is_auto_work', 1)->get();

        foreach ($organization as $item) {
            $time = collect(explode(' - ', $item->workTime));

            $startTime = Carbon::parse(Carbon::parse($time->get(0))->format('Y-m-d H:i:s'));
            $endTime = Carbon::parse(Carbon::parse($time->get(1))->format('Y-m-d H:i:s'));

            if($startTime->getTimestamp() <= Carbon::now()->getTimestamp()){
                $item->update(['isActive' => 1]);
            } else $item->update(['isActive' => 0]);

            if($endTime->getTimestamp() < Carbon::now()->getTimestamp()){
                $item->update(['isActive' => 0]);
            }
        }
        return 0;
    }
}
