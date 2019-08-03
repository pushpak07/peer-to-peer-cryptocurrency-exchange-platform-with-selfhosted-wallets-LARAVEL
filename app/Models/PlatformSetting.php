<?php

namespace App\Models;

use App\Logics\Support\Template;
use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get a template instance
     *
     * @return Template
     */
    public function template()
    {
        return new Template(
            $this->template, $this->theme_color
        );
    }

    /**
     * @return array
     */
    public function allowedPublicIps()
    {
        $list = array();

        if($value = $this->allowed_public_ip){
            $values = explode(',', $value);

            $list = array_map('trim', $values);
        }

        return $list;
    }
}
