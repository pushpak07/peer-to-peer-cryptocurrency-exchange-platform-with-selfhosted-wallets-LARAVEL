<?php

namespace App\Channels;

use App\Models\User;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioChannel
{
	/**
	 * Twilio Configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Create a new channel instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->config = config()->get('services.twilio');
	}

	/**
	 * Returns an instance of the driver
	 *
	 * @return Client
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
	protected function instance()
	{
		return new Client(
			$this->config['id'],
			$this->config['token']
		);
	}

	/**
	 * Send the given notification.
	 *
	 * @param mixed $notifiable
	 * @param \Illuminate\Notifications\Notification $notification
	 * @throws \Twilio\Exceptions\ConfigurationException
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

		$this->instance()->messages->create(
			$to, [
				'from' => $message->from ?: $this->config['number'],
				'body' => trim($message->content)
			]
		);
	}

}
