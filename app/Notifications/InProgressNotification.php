<?php

namespace App\Notifications;

use App\Models\Order\Order;
use App\Models\UserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class InProgressNotification extends Notification
{
    use Queueable;

    private $order;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $user_event = new UserEvent();
        $user_event->user_id = $notifiable->id;
        $user_event->title = 'Hey!';

        $user_event->values = [
            'type' => 'created',
            'orderId' => $this->order->iiko_order_number ?? ''
        ];

        $user_event->save();

        if(!empty($notifiable->onsignal_token)) {
            return [OneSignalChannel::class];
        }
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * @param $notifiable
     * @return OneSignalMessage
     */
    public function toOneSignal($notifiable)
    {
        app()->setLocale($notifiable->language);

        return OneSignalMessage::create()
            ->setSubject(__('events.order_title'))
            ->setBody(__('events.order_start', [
                'id' => $this->order->iiko_order_number
            ]))
            ->setData('url',route('main'))
            ->setIcon('https://bufet.vrpro.com.ua/image/logo.svg');
    }
}
