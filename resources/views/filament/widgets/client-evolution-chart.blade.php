<x-filament-widgets::widget>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Évolution des Clients</h3>
        </div>

        <!-- Stats Summary -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                    <div class="text-3xl font-bold text-emerald-600 mb-1">{{ $this->getTotalClientsThisMonth() }}</div>
                    <div class="text-sm font-medium text-emerald-700">Nouveaux ce mois</div>
                    <div class="text-xs text-emerald-600 mt-1">Croissance client</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-3xl font-bold text-blue-600 mb-1">{{ $this->getTotalClients() }}</div>
                    <div class="text-sm font-medium text-blue-700">Total clients</div>
                    <div class="text-xs text-blue-600 mt-1">Base de données</div>
                </div>
            </div>

            <!-- Additional metrics -->
            <div class="mt-4 grid grid-cols-1 gap-3">
                <div class="text-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="text-lg font-semibold text-gray-700">{{ number_format($this->getAverageClientsPerMonth(), 1) }}</div>
                    <div class="text-xs text-gray-600">Clients par mois (moyenne)</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>