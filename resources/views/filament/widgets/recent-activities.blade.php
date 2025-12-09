<x-filament-widgets::widget>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Activité récente</h3>
            </div>
        </div>

        <!-- Activities List -->
        <div class="px-6 py-4">
            <div class="space-y-3">
                @forelse($this->getRecentActivities() as $activity)
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150 group">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-medium text-sm">
                                {{ strtoupper(substr($activity->user?->name ?? 'S', 0, 1)) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-900">{{ $activity->user?->name ?? 'Système' }}</span>
                                    </div>
                                    @if($activity->description || $activity->task?->title)
                                        <div class="text-xs text-gray-500 truncate mt-0.5" title="{{ $activity->description ?? $activity->task?->title }}">
                                            {{ $activity->description ?? $activity->task?->title }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Time -->
                                <div class="flex-shrink-0 ml-3">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md whitespace-nowrap">
                                        {{ $activity->time_ago }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Aucune activité récente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>