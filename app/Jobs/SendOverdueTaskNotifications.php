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

        // Get all users who should receive notifications
        $users = User::all();

        foreach ($users as $user) {
            $this->sendNotificationsToUser($user, $overdueTasks, $upcomingTasks);
        }

        Log::info("Sent notifications for {$overdueTasks->count()} overdue and {$upcomingTasks->count()} upcoming tasks");
    }

    /**
     * Send appropriate notifications to a specific user based on their role and permissions.
     */
    private function sendNotificationsToUser(User $user, Collection $allOverdueTasks, Collection $allUpcomingTasks): void
    {
        // Filter tasks based on user permissions
        $userOverdueTasks = $this->filterTasksForUser($user, $allOverdueTasks);
        $userUpcomingTasks = $this->filterTasksForUser($user, $allUpcomingTasks);

        // Send notifications if user has relevant tasks
        if ($userOverdueTasks->isNotEmpty()) {
            $isAdminNotification = $this->shouldSendAsAdminNotification($user, $userOverdueTasks);
            Notification::send($user, new OverdueTaskNotification($userOverdueTasks, $isAdminNotification));
        }

        if ($userUpcomingTasks->isNotEmpty()) {
            $isAdminNotification = $this->shouldSendAsAdminNotification($user, $userUpcomingTasks);
            Notification::send($user, new \App\Notifications\UpcomingTaskNotification($userUpcomingTasks, $isAdminNotification));
        }
    }

    /**
     * Filter tasks that the user should be notified about based on their role.
     */
    private function filterTasksForUser(User $user, Collection $tasks): Collection
    {
        return $tasks->filter(function ($task) use ($user) {
            // If task is assigned to user, they should be notified
            if ($task->user_id === $user->id) {
                return true;
            }

            // If user can see all clients, they get all unassigned tasks
            if ($user->canSeeAllClients()) {
                return true;
            }

            // For commercial users, only notify about tasks related to their clients
            if ($user->hasRole('commercial')) {
                return $task->client && $task->client->user_id === $user->id;
            }

            // Viewers don't get notifications for unassigned tasks
            return false;
        });
    }

    /**
     * Determine if notification should be sent as admin/system notification.
     */
    private function shouldSendAsAdminNotification(User $user, Collection $tasks): bool
    {
        // If any task is not assigned to this user, it's an admin notification
        return $tasks->contains(function ($task) use ($user) {
            return $task->user_id !== $user->id;
        });
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['overdue-tasks', 'notifications'];
    }
}