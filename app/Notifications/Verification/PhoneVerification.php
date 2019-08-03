<?php

namespace App\Notifications\Verification;

use App\Notifications\Messages\SmsMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class PhoneVerification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];

        array_push($channels, getSmsChannel());

        return $channels;
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        $notifiable->generateToken();

        $token = $notifiable->token;

        $minutes = Carbon::parse($notifiable->token_expiry)
            ->diffInMinutes(now());

        return (new NexmoMessage)
            ->content(Lang::get('sms.verification.text',[
                'token' => $token, 'minutes' => $minutes
            ]));
    }

    /**
     * Get the Other / SMS representation of the notification.
     *
     * @param $notifiable
     * @return SmsMessage
     */
    public function toSms($notifiable)
    {
        $notifiable->generateToken();

        $token = $notifiable->token;

        $minutes = Carbon::parse($notifiable->token_expiry)
            ->diffInMinutes(now());

        return (new SmsMessage)
            ->content(Lang::get('sms.verification.text',[
                'token' => $token, 'minutes' => $minutes
            ]));
    }

}
