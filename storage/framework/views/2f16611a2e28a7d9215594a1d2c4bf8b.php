<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100" style="grid-column: span 2;" x-data="{ activeTab: 'clients' }">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Indicateurs de Performance</h3>
    
    <!-- Tabs -->
    <div class="flex space-x-1 border-b border-gray-100 mb-6">
        <button @click="activeTab = 'clients'" 
                :class="activeTab === 'clients' ? 'text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px flex items-center space-x-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Clients</span>
        </button>
        <button @click="activeTab = 'tasks'" 
                :class="activeTab === 'tasks' ? 'text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px flex items-center space-x-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Tâches</span>
        </button>
        <button @click="activeTab = 'contacts'" 
                :class="activeTab === 'contacts' ? 'text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px flex items-center space-x-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Contacts</span>
        </button>
        <button @click="activeTab = 'activities'" 
                :class="activeTab === 'activities' ? 'text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px flex items-center space-x-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span>Activités</span>
        </button>
    </div>

    <!-- Tab Content: Clients -->
    <div x-show="activeTab === 'clients'" x-transition class="grid grid-cols-3 gap-6">
        <div class="p-4 rounded-lg bg-blue-50 border border-blue-100">
            <span class="text-xs font-medium text-blue-700">Total Clients</span>
            <div class="text-3xl font-bold text-blue-900 mt-2"><?php echo e($this->getTotalClients()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-blue-50 border border-blue-100">
            <span class="text-xs font-medium text-blue-700">Clients Actifs</span>
            <div class="text-3xl font-bold text-blue-900 mt-2"><?php echo e($this->getActiveClients()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-blue-50 border border-blue-100">
            <span class="text-xs font-medium text-blue-700">Clients avec Tâches</span>
            <div class="text-3xl font-bold text-blue-900 mt-2"><?php echo e($this->getClientsWithTasks()); ?></div>
        </div>
    </div>

    <!-- Tab Content: Tasks -->
    <div x-show="activeTab === 'tasks'" x-transition class="grid grid-cols-3 gap-6">
        <div class="p-4 rounded-lg bg-purple-50 border border-purple-100">
            <span class="text-xs font-medium text-purple-700">Total Tâches</span>
            <div class="text-3xl font-bold text-purple-900 mt-2"><?php echo e($this->getTotalTasks()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-purple-50 border border-purple-100">
            <span class="text-xs font-medium text-purple-700">Tâches en cours</span>
            <div class="text-3xl font-bold text-purple-900 mt-2"><?php echo e($this->getInProgressTasks()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-purple-50 border border-purple-100">
            <span class="text-xs font-medium text-purple-700">Tâches terminées</span>
            <div class="text-3xl font-bold text-purple-900 mt-2"><?php echo e($this->getCompletedTasks()); ?></div>
        </div>
    </div>

    <!-- Tab Content: Contacts -->
    <div x-show="activeTab === 'contacts'" x-transition class="grid grid-cols-3 gap-6">
        <div class="p-4 rounded-lg bg-green-50 border border-green-100">
            <span class="text-xs font-medium text-green-700">Total Contacts</span>
            <div class="text-3xl font-bold text-green-900 mt-2"><?php echo e($this->getTotalContacts()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-green-50 border border-green-100">
            <span class="text-xs font-medium text-green-700">Contacts par Client</span>
            <div class="text-3xl font-bold text-green-900 mt-2"><?php echo e($this->getAverageContactsPerClient()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-green-50 border border-green-100">
            <span class="text-xs font-medium text-green-700">Contacts Récents</span>
            <div class="text-3xl font-bold text-green-900 mt-2"><?php echo e($this->getRecentContacts()); ?></div>
        </div>
    </div>

    <!-- Tab Content: Activities -->
    <div x-show="activeTab === 'activities'" x-transition class="grid grid-cols-3 gap-6">
        <div class="p-4 rounded-lg bg-orange-50 border border-orange-100">
            <span class="text-xs font-medium text-orange-700">Total Activités</span>
            <div class="text-3xl font-bold text-orange-900 mt-2"><?php echo e($this->getTotalActivities()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-orange-50 border border-orange-100">
            <span class="text-xs font-medium text-orange-700">Activités (7j)</span>
            <div class="text-3xl font-bold text-orange-900 mt-2"><?php echo e($this->getRecentActivitiesCount()); ?></div>
        </div>
        <div class="p-4 rounded-lg bg-orange-50 border border-orange-100">
            <span class="text-xs font-medium text-orange-700">Activités Aujourd'hui</span>
            <div class="text-3xl font-bold text-orange-900 mt-2"><?php echo e($this->getTodayActivities()); ?></div>
        </div>
    </div>
</div><?php /**PATH D:\Projet\web\crm_leger\resources\views/filament/widgets/performance-indicators.blade.php ENDPATH**/ ?>