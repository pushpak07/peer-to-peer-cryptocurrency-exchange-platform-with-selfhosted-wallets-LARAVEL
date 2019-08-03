<?php

namespace App\Providers;

use App\Logics\Adapters\BitcoinAdapter;
use App\Models\User;
use function foo\func;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->prepareDatabase();
        $this->loadBladeDirective();
        $this->forceURLScheme();
    }

    /**
     * Prepare database schema
     *
     * @return void
     */
    private function prepareDatabase()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Force https on url
     *
     * @return void
     */
    private function forceURLScheme()
    {
        if (env('APP_REDIRECT_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register blade directives
     *
     * @return void
     */
    private function loadBladeDirective()
    {
        Blade::directive('alloworcan', function ($arguments) {
            list($permission, $user) = explode(',', $arguments);

            return "<?php if(auth()->user()->can({$permission}) || {$user} == auth()->user()->id):?>";
        });

        Blade::directive('endalloworcan', function () {
            return '<?php endif; ?>';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\JeroenG\Packager\PackagerServiceProvider::class);
        }
    }
}
