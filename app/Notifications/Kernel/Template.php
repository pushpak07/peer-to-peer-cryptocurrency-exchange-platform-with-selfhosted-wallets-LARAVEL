<?php

namespace App\Notifications\Kernel;


use App\Models\NotificationTemplate;
use App\Models\User;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Support\Facades\Lang;

trait Template
{

    /**
     * Get action object
     *
     * @var array|bool
     */
    public $action;

    /**
     * Email Template
     *
     * @var NotificationTemplate
     */
    public $template;

    /**
     * Get the notification's delivery channels.
     *
     * @param  User $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $this->replacement = $this->replacement($notifiable);

        $channels = json_decode(self::getChannels(), true);

        if (method_exists($this, 'customChannels')) {
            $channels = array_intersect(
                $channels, $this->customChannels($notifiable)
            );
        }

        return $channels;
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->setLangReplacement($notifiable);
        $this->setMailAction($notifiable);

        return (new MailMessage())
            ->level($this->template->level)
            ->subject(__($this->template->subject, $this->replacement))
            ->markdown('markdown.email.default', [
                'template' => $this->template,
                'replacement' => $this->replacement,
                'action' => $this->action
            ]);
    }

    /**
     * Get the email action button
     *
     * @param $notifiable
     */
    public function setMailAction($notifiable)
    {
        if ($this->template->action_editable) {
            $this->action = json_decode($this->template->action, true);
        } else {
            $this->action = $this->action($notifiable);
        }
    }

    /**
     * Set language replacements
     *
     * @param $notifiable
     */
    public function setLangReplacement($notifiable)
    {
        $this->replacement = $this->replacement($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->setLangReplacement($notifiable);
        $this->setMailAction($notifiable);


        return [
            'link' => $this->action['url'] ?? '#',
            'icon_class' => "ft-bell icon-bg-circle " . bg_status_class(self::$level),
            'subject' => __($this->template->subject, $this->replacement),
            'message' => __($this->template->message, $this->replacement),
        ];
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        $this->setLangReplacement($notifiable);

        $message = __($this->template->message, $this->replacement);

        return (new NexmoMessage())->content($message);
    }

    /**
     * Get the Other / SMS representation of the notification.
     *
     * @param $notifiable
     * @return SmsMessage
     */
    public function toSms($notifiable)
    {
        $this->setLangReplacement($notifiable);

        $message = __($this->template->message, $this->replacement);

        return (new SmsMessage())->content($message);
    }

    /**
     * Get the configuration instance for
     * this notification
     *
     * @return Configuration
     */
    public static function getConfiguration()
    {
        return new Configuration(self::$template_name, [
            'level' => self::$level,
            'action_editable' => self::$action_editable,
            'channels' => self::getChannels(),
        ], self::parameters());
    }

    /**
     * Get json encoded value of the channels
     * array
     *
     * @return string
     */
    private static function getChannels()
    {
        $channels = [];

        if (in_array('sms', self::$channels)) {
            array_push($channels, getSmsChannel());
        }

        if (in_array('database', self::$channels)) {
            array_push($channels, 'database');
        }

        if (in_array('mail', self::$channels)) {
            array_push($channels, 'mail');
        }

        return json_encode($channels);
    }
}
