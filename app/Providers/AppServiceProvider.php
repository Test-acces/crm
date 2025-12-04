<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Task;
use App\Observers\ClientObserver;
use App\Observers\ContactObserver;
use App\Observers\TaskObserver;
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
        // Register model observers
        Client::observe(ClientObserver::class);
        Contact::observe(ContactObserver::class);
        Task::observe(TaskObserver::class);
    }
}
