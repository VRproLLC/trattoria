<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use OneSignal\Config as OneSignalConfig;
use OneSignal\OneSignal;
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbara:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка пушов';

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
    public function handle()
    {
        $config = new OneSignalConfig(\Config::get('values.oneApplicationId'), \Config::get('values.oneApplicationAuthKey'));
        $httpClient = new Psr18Client();
        $requestFactory = $streamFactory = new Psr17Factory();
        $oneSignal = new OneSignal($config, $httpClient, $requestFactory, $streamFactory);


        $notifications = Notification::where('is_status', 0)->where('send_time', '<', Carbon::now())->get();


        foreach ($notifications as $notification){
            $data = [
                'contents' => [
                    'en' => $notification->text,
                    'uk' =>  $notification->text,
                    'ru' =>   $notification->text,
                ],
                'headings' => [
                    'en' => $notification->subject,
                    'uk' =>  $notification->subject,
                    'ru' =>   $notification->subject,
                ],
                'included_segments' => ['All'],
                //'include_player_ids' => ['238a0b95-f454-4971-980f-0807a02b0eb8']
            ];

            if($notification->link){
                if(!stristr($notification->link, 'https')) {
                    $data['url'] = 'https://' . $notification->link;
                } else $data['url'] = $notification->link;
            }
            $oneSignal->notifications()->add($data);

            $notification->is_status = 1;
            $notification->save();
        }
        return 0;
    }
}
