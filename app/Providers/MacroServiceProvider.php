<?php

namespace App\Providers;

use Collective\Html\HtmlServiceProvider;

/**
 * Class MacroServiceProvider.
 */
class MacroServiceProvider extends HtmlServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // Load Macros
        require base_path('/app/Macros/HtmlMacros.php');
        require base_path('/app/Macros/FormMacros.php');
    }
}
