<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200" style="grid-column: 1 / -1;">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Actions Rapides</h3>
    <div class="space-y-0">
            <div class="grid grid-cols-6 gap-3">
                <!-- New Organization - Orange pastel -->
                <a href="<?php echo e(route('filament.admin.resources.clients.create')); ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-orange-50 hover:bg-orange-100 border border-orange-100 transition-colors">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="text-xs font-medium text-gray-700">Nouveau Client</span>
                </a>

                <!-- New Container - Bleu pastel -->
                <a href="<?php echo e(route('filament.admin.resources.contacts.create')); ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-100 transition-colors">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-xs font-medium text-gray-700">Nouveau Contact</span>
                </a>

                <!-- New Leasing - Vert pastel -->
                <a href="<?php echo e(route('filament.admin.resources.tasks.create')); ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-green-50 hover:bg-green-100 border border-green-100 transition-colors">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-xs font-medium text-gray-700">Nouvelle Tâche</span>
                </a>

                <!-- New Trading - Violet pastel -->
                <a href="<?php echo e(route('filament.admin.resources.activities.index')); ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-purple-50 hover:bg-purple-100 border border-purple-100 transition-colors">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="text-xs font-medium text-gray-700">Nouvelle Activité</span>
                </a>

                <!-- Quick Pickup - Teal pastel -->
                <a href="<?php echo e(route('filament.admin.resources.clients.index')); ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-teal-50 hover:bg-teal-100 border border-teal-100 transition-colors">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span class="text-xs font-medium text-gray-700">Voir Clients</span>
                </a>

                <!-- Quick Drop Off - Amber pastel -->
                <a href="<?php echo e(route('filament.admin.resources.tasks.index')); ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-100 transition-colors">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-xs font-medium text-gray-700">Voir Tâches</span>
                </a>
            </div>
    </div>
</div><?php /**PATH D:\Projet\web\crm_leger\resources\views/filament/widgets/quick-actions-grid.blade.php ENDPATH**/ ?>