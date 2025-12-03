<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Notifications
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Stay updated with your tasks and activities
                </p>
            </div>

            @if($this->getNotifications()->total() > 0)
                <x-filament::button
                    wire:click="markAllAsRead"
                    color="gray"
                    size="sm"
                >
                    Mark All as Read
                </x-filament::button>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($this->getNotifications() as $notification)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-l-blue-500' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                @if(!$notification->read_at)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                @endif
                                <h3 class="font-medium text-gray-900 dark:text-white">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </h3>
                            </div>

                            @if(isset($notification->data['task_count']))
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $notification->data['task_count'] }} task{{ $notification->data['task_count'] > 1 ? 's' : '' }}
                                </p>
                            @endif

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if(!$notification->read_at)
                            <x-filament::button
                                wire:click="markAsRead({{ $notification->id }})"
                                color="gray"
                                size="xs"
                                variant="outline"
                            >
                                Mark as Read
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <x-heroicon-o-bell class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        No notifications
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You're all caught up! Check back later for updates.
                    </p>
                </div>
            @endforelse
        </div>

        @if($this->getNotifications()->hasPages())
            <div class="flex justify-center">
                {{ $this->getNotifications()->links() }}
            </div>
        @endif
    </div>
</x-filament-panels::page>