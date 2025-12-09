<div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 w-full">
    <div class="flex items-start justify-between w-full">
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-900 truncate w-full">
                {{ $clientName }}
            </h4>
            <p class="text-sm text-gray-600 mt-1 w-full">
                {{ $description }}
            </p>
            @if(isset($task) && $task)
                <p class="text-xs text-primary-600 mt-2 flex items-center w-full">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="truncate">{{ $task }}</span>
                </p>
            @endif
        </div>
        <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
            <div class="flex items-center text-xs text-gray-500">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $timeAgo }}
            </div>
            <div class="flex items-center justify-center w-8 h-8 bg-{{ $color }}-100 rounded-full">
                <span class="text-sm">{{ $icon }}</span>
            </div>
        </div>
    </div>
</div>