<?php

namespace App\DTOs;

readonly class CrmStatsDTO
{
    public function __construct(
        public int $totalClients,
        public int $activeClients,
        public int $inactiveClients,
        public int $totalContacts,
        public int $inProgressTasks,
        public int $completedTasks,
        public int $recentActivities,
        public array $taskStatusDistribution,
        public array $clientStatusDistribution,
        public array $overdueTasks,
        public array $upcomingTasks,
    ) {}

    public static function fromModels(): self
    {
        $totalClients = \App\Models\Client::count();
        $activeClients = \App\Models\Client::active()->count();
        $inactiveClients = \App\Models\Client::inactive()->count();
        $totalContacts = \App\Models\Contact::count();
        $inProgressTasks = \App\Models\Task::inProgress()->count();
        $completedTasks = \App\Models\Task::completed()->count();
        $recentActivities = \App\Models\Activity::recent()->count();

        // Task status distribution for charts
        $taskStatusDistribution = [
            'pending' => \App\Models\Task::pending()->count(),
            'in_progress' => $inProgressTasks,
            'completed' => $completedTasks,
        ];

        // Client status distribution for charts
        $clientStatusDistribution = [
            'active' => $activeClients,
            'inactive' => $inactiveClients,
        ];

        // Overdue tasks (last 5)
        $overdueTasks = \App\Models\Task::overdue()
            ->with(['client', 'user'])
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->map(fn ($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'client' => $task->client->name,
                'due_date' => $task->due_date->format('M j, Y'),
                'days_overdue' => abs($task->days_until_due),
            ])
            ->toArray();

        // Upcoming tasks (next 7 days)
        $upcomingTasks = \App\Models\Task::dueSoon()
            ->with(['client', 'user'])
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->map(fn ($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'client' => $task->client->name,
                'due_date' => $task->due_date->format('M j, Y'),
                'days_remaining' => $task->days_until_due,
            ])
            ->toArray();

        return new self(
            totalClients: $totalClients,
            activeClients: $activeClients,
            inactiveClients: $inactiveClients,
            totalContacts: $totalContacts,
            inProgressTasks: $inProgressTasks,
            completedTasks: $completedTasks,
            recentActivities: $recentActivities,
            taskStatusDistribution: $taskStatusDistribution,
            clientStatusDistribution: $clientStatusDistribution,
            overdueTasks: $overdueTasks,
            upcomingTasks: $upcomingTasks,
        );
    }

    public function toArray(): array
    {
        return [
            'total_clients' => $this->totalClients,
            'active_clients' => $this->activeClients,
            'inactive_clients' => $this->inactiveClients,
            'total_contacts' => $this->totalContacts,
            'in_progress_tasks' => $this->inProgressTasks,
            'completed_tasks' => $this->completedTasks,
            'recent_activities' => $this->recentActivities,
            'task_status_distribution' => $this->taskStatusDistribution,
            'client_status_distribution' => $this->clientStatusDistribution,
            'overdue_tasks' => $this->overdueTasks,
            'upcoming_tasks' => $this->upcomingTasks,
        ];
    }

    public function getCompletionRate(): float
    {
        $totalTasks = array_sum($this->taskStatusDistribution);
        return $totalTasks > 0 ? round(($this->completedTasks / $totalTasks) * 100, 1) : 0.0;
    }

    public function getActiveClientRate(): float
    {
        return $this->totalClients > 0 ? round(($this->activeClients / $this->totalClients) * 100, 1) : 0.0;
    }
}