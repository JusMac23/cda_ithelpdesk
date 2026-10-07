<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Added this import
use Illuminate\Pagination\Paginator;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Authentik\AuthentikExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. Added this block to force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 3. Use Tailwind CSS for Laravel pagination
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        // Hook Authentik into Socialite (Your existing code kept intact)
        $this->app->events->listen(
            SocialiteWasCalled::class,
            [AuthentikExtendSocialite::class, 'handle']
        );
    }
}