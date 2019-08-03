<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Get
     *
     * @return array
     */
    public function channels()
    {
        return json_decode($this->channels, true);
    }

    /**
     * Get the template action
     *
     * @return array|null
     */
    public function action()
    {
        if($this->action_editable){
            $action = json_decode($this->action, true);

            if(!array_key_exists('url', $action) || !array_key_exists('text', $action)){
                $action = ['url' => '', 'text' => ''];
            }

            return $action;
        }

        return null;
    }

    /**
     * Check if template supports sms channel
     *
     * @return bool
     */
    public function hasSmsChannel()
    {
        return (boolean) in_array(
            getSmsChannel(), $this->channels()
        );
    }

    /**
     * Check if template supports database channel
     *
     * @return bool
     */
    public function hasDatabaseChannel()
    {
        return in_array('database', $this->channels());
    }

    /**
     * Check if template supports mail channel
     *
     * @return bool
     */
    public function hasMailChannel()
    {
        return in_array('mail', $this->channels());
    }
}
