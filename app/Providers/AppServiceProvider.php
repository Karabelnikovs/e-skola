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
            if (!$this->app->environment('local') && str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }
    }
}
