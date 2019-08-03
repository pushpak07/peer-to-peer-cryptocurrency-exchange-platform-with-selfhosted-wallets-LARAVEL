<?php

namespace App\Channels;

use AfricasTalking\SDK\AfricasTalking;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Notifications\Notification;

class AfricasTalkingChannel
{

    /**
     * Africas Talking API Key
     *
     * @var mixed
     */
    protected $config;

    /**
     * Create an AfricasTalking channel
     *
     * AfricasTalkingChannel constructor.
     */
    public function __construct()
    {
        $this->config = config()->get('services.africastalking');
    }

    /**
     * Returns an instance of the driver
     *
     * @return AfricasTalking
     */
    protected function instance()
    {
        return new AfricasTalking(
            $this->config['username'],
            $this->config['key']
        );
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
