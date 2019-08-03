<?php

namespace App\Notifications\Verification;

use App\Models\NotificationTemplate;
use App\Notifications\Kernel\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification
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
    protected static $template_name = 'email_verification';

    /**
     * Default notification channel
     * e.g mail, database, sms
     *
     * @var array
     */
    private static $channels = ['mail'];

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
    public function __construct($token, $expiration)
    {
        $this->template = self::getConfiguration()->template();
        $this->token = $token;
        $this->expiration = $expiration;
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
            'url' => $this->link($notifiable),
            'text' => __('Verify Now')
        ];
    }

    private function link($notifiable)
    {
        return route('verifyEmailLink', [
            'email' => $notifiable->email,
            'expiration' => $this->expiration,
            'token' => $this->token
        ]);
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
            'link' => $this->link($notifiable)
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
            ':link' => 'Verification Link'
        ];
    }
}
