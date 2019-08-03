<?php

namespace App\Notifications\Trades;

use App\Models\NotificationTemplate;
use App\Models\Trade;
use App\Notifications\Kernel\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Rated extends Notification
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
    protected static $template_name = 'trade_rated';

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
     * Comment on rating
     *
     * @var string
     */
    public $comment;

    /**
     * Score of rating
     *
     * @var integer
     */
    public $score;

    /**
     * Create a new notification instance.
     *
     * @param Trade $trade
     * @param $score
     * @param $comment
     * @return void
     */
    public function __construct($trade, $score, $comment)
    {
        $this->template = self::getConfiguration()->template();
        $this->trade = $trade;
        $this->score = $score;
        $this->comment = $comment;
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
            'token' => $this->trade->token,
            'score' => $this->score,
            'comment' => $this->comment,
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
            ':score' => 'Rating Score',
            ':comment' => 'Rating Comment',
        ];
    }
}
