<?php

namespace App\Providers;

use App\Services\TelegramBotService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton("telegram_bot", function () {
            return new TelegramBotService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config("app.env") === "production") {
            \URL::forceScheme("https");
        }
    }
}
