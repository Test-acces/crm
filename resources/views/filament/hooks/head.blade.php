<!-- Tailwind CSS v4 CDN -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<!-- Dashboard Grid CSS -->
<style>
/* Force grid layout for dashboard widgets */
@media (min-width: 768px) {
    /* Make sidebar activities span 2 rows */
    .fi-wi-sidebar-activities {
        grid-row: span 2 !important;
    }
}
</style>

<style type="text/tailwindcss">
    @theme {
        --color-primary-50: #eff6ff;
        --color-primary-100: #dbeafe;
        --color-primary-200: #bfdbfe;
        --color-primary-300: #93c5fd;
        --color-primary-400: #60a5fa;
        --color-primary-500: #3b82f6;
        --color-primary-600: #2563eb;
        --color-primary-700: #1d4ed8;
        --color-primary-800: #1e40af;
        --color-primary-900: #1e3a8a;
        --color-gray-50: #f9fafb;
        --color-gray-100: #f3f4f6;
        --color-gray-200: #e5e7eb;
        --color-gray-300: #d1d5db;
        --color-gray-400: #9ca3af;
        --color-gray-500: #6b7280;
        --color-gray-600: #4b5563;
        --color-gray-700: #374151;
        --color-gray-800: #1f2937;
        --color-gray-900: #111827;
    }
</style>

<!-- Chart.js from CDNJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

<!-- Dashboard Charts Script -->
<script>
    window.initDashboardCharts = function(clientEvolutionData, clientStatusData, taskStatusData) {
        // Client Evolution Chart
        const clientEvolutionCtx = document.getElementById('clientEvolutionChart');
        if (clientEvolutionCtx && typeof Chart !== 'undefined') {
            new Chart(clientEvolutionCtx.getContext('2d'), {
                type: 'line',
                data: clientEvolutionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                            },
                        },
                    },
                },
            });
        }

        // Client Status Chart
        const clientStatusCtx = document.getElementById('clientStatusChart');
        if (clientStatusCtx && typeof Chart !== 'undefined') {
            new Chart(clientStatusCtx.getContext('2d'), {
                type: 'pie',
                data: clientStatusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    },
                },
            });
        }

        // Task Status Chart
        const taskStatusCtx = document.getElementById('taskStatusChart');
        if (taskStatusCtx && typeof Chart !== 'undefined') {
            new Chart(taskStatusCtx.getContext('2d'), {
                type: 'bar',
                data: taskStatusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                            },
                        },
                    },
                },
            });
        }
    };
</script>