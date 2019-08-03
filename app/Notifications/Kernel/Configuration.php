<?php

namespace App\Notifications\Kernel;


use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Lang;

class Configuration
{
    /**
     * The name of the notification template
     *
     * @var string
     */
    private $template_name;

    /**
     * The default parameters of the notification
     * template
     *
     * @var array
     */
    private $default;

    /**
     * The default replacement array for the template
     *
     * @var array
     */
    private $parameters;

    /**
     * @var NotificationTemplate|null
     */
    private $template = null;

    /**
     * Configuration constructor.
     *
     * @param $template
     * @param $default
     * @param $parameters
     */
    public function __construct($template, $default, $parameters)
    {
        $this->template_name = $template;
        $this->default = $default;
        $this->parameters = $parameters;
    }

    /**
     * Get template instance
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function template()
    {
        if (!$this->template) {
            $this->template = NotificationTemplate::firstOrCreate([
                'name' => $this->template_name
            ], $this->getDefaultRecords());
        }

        return $this->template;
    }

    /**
     * Get the default parameters of the notification
     * template
     *
     * @return array
     */
    private function getDefaultRecords()
    {
        return array_merge([
            'action' => json_encode($this->action()),
            'subject' => $this->getLang('subject'),
            'intro_line' => $this->getLang('intro_line'),
            'outro_line' => $this->getLang('outro_line'),
            'message' => $this->getLang('message'),
        ], $this->default);
    }

    /**
     * Get default mail action
     *
     * @return array
     */
    public function action()
    {
        return ['url' => '', 'text' => ''];
    }

    /**
     * Get parameter details
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Get the default language value of a key
     *
     * @param $key
     * @return null
     */
    private function getLang($key)
    {
        return Lang::get('notification.' . $this->template_name)[$key] ?: null;
    }
}
