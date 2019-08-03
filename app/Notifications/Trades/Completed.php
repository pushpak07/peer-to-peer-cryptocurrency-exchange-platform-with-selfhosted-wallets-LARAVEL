<?php

namespace App\Notifications\Trades;

use App\Models\NotificationTemplate;
use App\Models\Trade;
use App\Notifications\Kernel\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Completed extends Notification
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
    protected static $level = 'success';

    /**
     * Template record
     *
     * @var string
     */
    protected static $template_name = 'trade_completed';

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
     * @var Trade
     */
    public $trade;
    /**
     * Create a new notification instance.
     *
     * @param Trade $trade
     * @return void
     */
    public function __construct($trade)
    {
        $this->template = self::getConfiguration()->template();
        $this->trade = $trade;
    }

    /**
     * Get json encoded value of the mail action
     *
     * @return array
     */
    private function action($notifiable)
    {
        return [
            'url' => route('home.trades.index', [
                'token' => $this->trade->token
            ]),
            'text' => __('View Trade')
        ];
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
            'app_name' => config('app.name'),
            'user_name' => $notifiable->name,
            'token' => $this->trade->token
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
            ':app_name' => 'Application Name',
            ':user_name' => 'Recipient Username',
            ':token' => 'Trade Token',
        ];
    }
}
