<?php

namespace App\Providers;

use App\Events\ClientCreated;
use App\Events\ContactCreated;
use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Events\ActivityCreated;
use App\Listeners\SendClientNotification;
use App\Listeners\SendContactNotification;
use App\Listeners\SendTaskNotification;
use App\Listeners\SendTaskUpdatedNotification;
use App\Listeners\SendActivityNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ClientCreated::class => [
            SendClientNotification::class,
        ],
        ContactCreated::class => [
            SendContactNotification::class,
        ],
        TaskCreated::class => [
            SendTaskNotification::class,
        ],
        TaskUpdated::class => [
            SendTaskUpdatedNotification::class,
        ],
        ActivityCreated::class => [
            SendActivityNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}