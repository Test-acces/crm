<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 h-full" style="grid-column: span 1; grid-row: span 2;">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Activité récente</h3>
    
    <div class="space-y-2 max-h-[600px] overflow-y-auto">
        @forelse($this->getRecentActivities() as $activity)
            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                <!-- Avatar avec couleur -->
                <div class="flex-shrink-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                        {{ strtoupper(substr($activity->user?->name ?? 'S', 0, 1)) }}
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $activity->user?->name ?? 'Système' }}
                            </p>
                            @if($activity->description || $activity->task?->title)
                                <p class="text-xs text-gray-600 line-clamp-2 mt-0.5" title="{{ $activity->description ?? $activity->task?->title }}">
                                    {{ $activity->description ?? $activity->task?->title }}
                                </p>
                            @endif
                        </div>

                        <!-- Time badge -->
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $activity->time_ago }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-900">Aucune activité récente</p>
                <p class="text-xs text-gray-500 mt-1">Les activités apparaîtront ici</p>
            </div>
        @endforelse
    </div>
</div>