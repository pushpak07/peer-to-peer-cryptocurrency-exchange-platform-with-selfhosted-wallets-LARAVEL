<?php

namespace App\Notifications\Transactions;

use App\Models\NotificationTemplate;
use App\Models\User;
use App\Notifications\Kernel\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class IncomingUnconfirmed extends Notification
{
    use Queueable, Template;

    /**
     * Notification Template
     *
     * @var NotificationTemplate
     */
    public $template;

    /**
     * Language Replacement
     *
     * @var array
     */
    public $replacement;

    /**
     * Email Level (info, success, error)
     *
     * @var
     */
    protected static $level = 'info';

    /**
     * Template record
     *
     * @var string
     */
    protected static $template_name = 'incoming_unconfirmed';

    /**
     * Default notification channel
     * e.g mail, database, sms
     *
     * @var array
     */
    private static $channels = ['mail', 'database'];

    /**
     * Allow/Disallow Custom Action
     *
     * @var bool
     */
    protected static $action_editable = false;

    /**
     * @var string
     */
    protected $coin;

    /**
     * @var string
     */
    protected $value;

    /**
     * Create a new notification instance.
     *
     * @param $coin
     * @param $value
     * @return void
     */
    public function __construct($coin, $value)
    {
        $this->value = coin($value, $coin)->getValue();
        $this->template = self::getConfiguration()->template();
        $this->coin = $coin;
    }

    /**
     * Get json encoded value of the mail action
     *
     * @return array
     */
    private function action($notifiable)
    {
        return [
            'url' => route('wallet.index'),
            'text' => __('My Wallet')
        ];
    }

    /**
     * Customize channel.
     *
     * @param  User $notifiable
     * @return array
     */
    public function customChannels($notifiable)
    {
        $channels = [];

        $settings = $notifiable->getNotificationSettings()
            ->where('name', 'coin_incoming_unconfirmed')
            ->first();

        if ($settings->sms) {
            array_push($channels, getSmsChannel());
        }

        if($settings->database){
            array_push($channels, 'database');
        }

        if($settings->email){
            array_push($channels, 'mail');
        }

        return $channels;
    }

    /**
     * Get lang replacement
     *
     * @param $notifiable
     * @return array
     */
    private function replacement($notifiable)
    {
        return [
            'coin' => get_coin($this->coin),
            'app_name' => config('app.name'),
            'user_name' => $notifiable->name,
            'value' => $this->value,
        ];
    }

    /**
     * Define language parameters
     *
     * @return array
     */
    private static function parameters()
    {
        return [
            ':coin' => 'Coin Wallet',
            ':app_name' => 'Application Name',
            ':user_name' => 'Username',
            ':value' => 'Amount',
        ];
    }
}
