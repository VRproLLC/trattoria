<?php

namespace App\Notifications;

use App\Models\UserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class OrderCancellationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            'type' => 'cancellation',
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
    public function toOneSignal($notifiable): OneSignalMessage
    {
        app()->setLocale($notifiable->language);

        return OneSignalMessage::create()
            ->setSubject('Hey!')
            ->setBody(__('events.cancellation'))
            ->setData('url',route('main'))
            ->setIcon('https://bufet.vrpro.com.ua/image/logo.svg');
    }
}
