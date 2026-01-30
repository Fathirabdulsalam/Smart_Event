<?php

namespace App\Providers;

use App\Models\MasterSocialMedia;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\PayLabsService;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register()
{
    $this->app->singleton(PayLabsService::class, function ($app) {
        return new PayLabsService();
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.user', function ($view) {
        $socialMedias = MasterSocialMedia::where('is_active', true)->get();
        $view->with('socialMedias', $socialMedias);

        if ($this->app->environment('local')) {
        URL::forceScheme('https');
    }
    });
    }
}
