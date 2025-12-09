<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200" style="grid-column: span 2;">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Vue d'Ensemble Générale</h3>
    
    <div class="grid grid-cols-4 gap-4">
        <!-- Total Clients -->
        <div class="space-y-3">
            <div class="h-5 flex items-center justify-between">
                <span class="text-xs font-medium text-gray-600">Total Clients</span>
            </div>
            <div class="text-3xl font-bold text-gray-900"><?php echo e($this->getTotalFleet()); ?></div>
            <div class="h-5 flex items-center text-xs text-blue-600">
                <span>Clients enregistrés</span>
            </div>
            <!-- Mini chart -->
            <div class="h-16 relative">
                <?php
                    $clientsData = $this->getClientsChartData();
                    $maxClients = max($clientsData) ?: 1;
                    $points = [];
                    foreach($clientsData as $index => $count) {
                        $x = ($index / (count($clientsData) - 1)) * 100;
                        $y = 100 - (($count / $maxClients) * 100);
                        $points[] = "$x,$y";
                    }
                    $pathData = 'M ' . implode(' L ', $points);
                ?>
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="<?php echo e($pathData); ?>" fill="none" stroke="rgb(147 197 253)" stroke-width="2" />
                    <path d="<?php echo e($pathData); ?> L 100,100 L 0,100 Z" fill="rgb(191 219 254)" opacity="0.3" />
                </svg>
            </div>
        </div>

        <!-- Total Tâches -->
        <div class="space-y-3">
            <div class="h-5 flex items-center justify-between">
                <span class="text-xs font-medium text-gray-600">Total Tâches</span>
            </div>
            <div class="text-3xl font-bold text-gray-900"><?php echo e(number_format($this->getTotalRevenue(), 0, ',', ' ')); ?></div>
            <div class="h-5 flex items-center text-xs text-purple-600">
                <span>Tâches créées</span>
            </div>
            <!-- Mini chart -->
            <div class="h-16 relative">
                <?php
                    $tasksData = $this->getTasksChartData();
                    $maxTasks = max($tasksData) ?: 1;
                    $points = [];
                    foreach($tasksData as $index => $count) {
                        $x = ($index / (count($tasksData) - 1)) * 100;
                        $y = 100 - (($count / $maxTasks) * 100);
                        $points[] = "$x,$y";
                    }
                    $pathData = 'M ' . implode(' L ', $points);
                ?>
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="<?php echo e($pathData); ?>" fill="none" stroke="rgb(192 132 252)" stroke-width="2" />
                    <path d="<?php echo e($pathData); ?> L 100,100 L 0,100 Z" fill="rgb(216 180 254)" opacity="0.3" />
                </svg>
            </div>
        </div>

        <!-- Tâches en attente -->
        <div class="space-y-3">
            <div class="h-5 flex items-center justify-between">
                <span class="text-xs font-medium text-gray-600">Tâches en attente</span>
            </div>
            <div class="text-3xl font-bold text-gray-900"><?php echo e(number_format($this->getRevenueToCollect(), 0, ',', ' ')); ?></div>
            <div class="h-5 flex items-center text-xs text-orange-600">
                <span>À traiter</span>
            </div>
            <!-- Mini chart -->
            <div class="h-16 relative">
                <?php
                    $pendingData = $this->getPendingTasksChartData();
                    $maxPending = max($pendingData) ?: 1;
                    $points = [];
                    foreach($pendingData as $index => $count) {
                        $x = ($index / (count($pendingData) - 1)) * 100;
                        $y = 100 - (($count / $maxPending) * 100);
                        $points[] = "$x,$y";
                    }
                    $pathData = 'M ' . implode(' L ', $points);
                ?>
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="<?php echo e($pathData); ?>" fill="none" stroke="rgb(251 146 60)" stroke-width="2" />
                    <path d="<?php echo e($pathData); ?> L 100,100 L 0,100 Z" fill="rgb(254 215 170)" opacity="0.3" />
                </svg>
            </div>
        </div>

        <!-- Taux de Complétion -->
        <div class="space-y-3">
            <div class="h-5 flex items-center justify-between">
                <span class="text-xs font-medium text-gray-600">Taux de Complétion</span>
            </div>
            <div class="text-3xl font-bold text-gray-900"><?php echo e($this->getOccupancyRate()); ?>%</div>
            <div class="h-5 flex items-center text-xs text-green-600">
                <span>Tâches terminées</span>
            </div>
            <!-- Mini chart -->
            <div class="h-16 relative">
                <?php
                    $completionData = $this->getCompletionRateChartData();
                    $maxCompletion = 100;
                    $points = [];
                    foreach($completionData as $index => $rate) {
                        $x = ($index / (count($completionData) - 1)) * 100;
                        $y = 100 - $rate;
                        $points[] = "$x,$y";
                    }
                    $pathData = 'M ' . implode(' L ', $points);
                ?>
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="<?php echo e($pathData); ?>" fill="none" stroke="rgb(74 222 128)" stroke-width="2" />
                    <path d="<?php echo e($pathData); ?> L 100,100 L 0,100 Z" fill="rgb(187 247 208)" opacity="0.3" />
                </svg>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\Projet\web\crm_leger\resources\views/filament/widgets/general-overview.blade.php ENDPATH**/ ?>