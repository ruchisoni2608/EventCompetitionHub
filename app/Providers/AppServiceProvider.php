<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Event;
use App\Services\OpenAIService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OpenAIService::class, function ($app) {
            // Provide your actual OpenAI API key here
            $apiKey = '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';

            return new OpenAIService($apiKey);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      View::composer('layouts.app', function ($view) {
        $events = Event::all();
        $selectedEventName = session('selectedEventName');

   

        $view->with('events', $events);
        $view->with('selectedEventName', $selectedEventName);
    });
    }
}