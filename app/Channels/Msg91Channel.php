<?php

namespace App\Channels;

use App\Notifications\Messages\SmsMessage;
use Illuminate\Notifications\Notification;

class Msg91Channel
{

    /**
     * Msg91 API Key
     *
     * @var mixed
     */
    protected $config;

    /**
     * Create an Msg91 channel
     *
     * Msg91Channel constructor.
     */
    public function __construct()
    {
        $this->config = config()->get('services.msg91');
    }

    /**
     * Returns an instance of the driver
     *
     * @return Msg91
     */
    protected function instance()
    {

    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('sms', $notification)) {
            return;
        }

        $message = $notification->toSms($notifiable);

        if (is_string($message)) {
            $message = new SmsMessage($message);
        }

        $this->instance()->sms()->send([
            'from' => $message->from ?: $this->config['from'],
            'to' => $to,
            'enqueue' => $this->config['enqueue'],
            'message' => trim($message->content)
        ]);
    }
}
