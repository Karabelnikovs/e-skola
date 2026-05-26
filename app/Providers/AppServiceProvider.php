<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('translate', function ($expression) {
            return "<?php echo \\App\\Helpers\\TranslationHelper::trans($expression); ?>";
        });

        $appUrl = config('app.url');
        if ($appUrl) {
            URL::forceRootUrl(rtrim($appUrl, '/'));
        }

        if ($this->app->environment('local')) {
            return;
        }

        $this->app->booted(function () {
            if (request()->isSecure()) {
                URL::forceScheme('https');
                config(['session.secure' => true]);
            } elseif (str_starts_with((string) config('app.url'), 'https://')) {
                URL::forceScheme('https');
                config(['session.secure' => true]);
            }
        });
    }
}
