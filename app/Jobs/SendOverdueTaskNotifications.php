<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OverdueTaskNotification;

class SendOverdueTaskNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all overdue tasks
        $overdueTasks = Task::overdue()
            ->with(['user', 'client', 'contact'])
            ->get();

        // Get tasks due in the next 3 days
        $upcomingTasks = Task::where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->where('status', '!=', 'completed')
            ->with(['user', 'client', 'contact'])
            ->get();

        if ($overdueTasks->isEmpty() && $upcomingTasks->isEmpty()) {
            Log::info('No overdue or upcoming tasks found');
            return;
        }

        // Group tasks by assigned user
        $overdueTasksByUser = $overdueTasks->groupBy('user_id');
        $upcomingTasksByUser = $upcomingTasks->groupBy('user_id');

        $allUserIds = collect(array_keys($overdueTasksByUser->toArray() + $upcomingTasksByUser->toArray()));

        foreach ($allUserIds as $userId) {
            $userOverdueTasks = $overdueTasksByUser->get($userId, collect());
            $userUpcomingTasks = $upcomingTasksByUser->get($userId, collect());

            if (!$userId) {
                // Tasks without assigned user - notify all users
                $users = User::all();
                foreach ($users as $user) {
                    if ($userOverdueTasks->isNotEmpty()) {
                        Notification::send($user, new OverdueTaskNotification($userOverdueTasks, true));
                    }
                    if ($userUpcomingTasks->isNotEmpty()) {
                        Notification::send($user, new \App\Notifications\UpcomingTaskNotification($userUpcomingTasks, true));
                    }
                }
                continue;
            }

            $user = User::find($userId);
            if ($user) {
                if ($userOverdueTasks->isNotEmpty()) {
                    Notification::send($user, new OverdueTaskNotification($userOverdueTasks, false));
                }
                if ($userUpcomingTasks->isNotEmpty()) {
                    Notification::send($user, new \App\Notifications\UpcomingTaskNotification($userUpcomingTasks, false));
                }
            }
        }

        Log::info("Sent notifications for {$overdueTasks->count()} overdue and {$upcomingTasks->count()} upcoming tasks");
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['overdue-tasks', 'notifications'];
    }
}