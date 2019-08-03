<?php

namespace App\Notifications\Verification;

use App\Models\NotificationTemplate;
use App\Notifications\Kernel\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailVerified extends Notification
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
     * The verification mail reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The verification mail expiration date.
     *
     * @var int
     */
    public $expiration;

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
    protected static $template_name = 'user_email_verified';

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
     * Create a new notification instance.
     *
     * @param $token
     * @param $expiration
     * @return void
     */
    public function __construct()
    {
        $this->template = self::getConfiguration()->template();
    }

    /**
     * Get json encoded value of the mail action
     *
     * @param $notifiable
     * @return array
     */
    private function action($notifiable)
    {
        return [
            'url' => route('login'),
            'text' => __('Continue')
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
            'user_name' => $notifiable->name
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
            ':user_name' => 'Recipient Username'
        ];
    }
}
