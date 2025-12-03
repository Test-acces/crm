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

        if ($overdueTasks->isEmpty()) {
            Log::info('No overdue tasks found');
            return;
        }

        // Group tasks by assigned user
        $tasksByUser = $overdueTasks->groupBy('user_id');

        foreach ($tasksByUser as $userId => $tasks) {
            if (!$userId) {
                // Tasks without assigned user - notify all users
                $users = User::all();
                foreach ($users as $user) {
                    Notification::send($user, new OverdueTaskNotification($tasks, true));
                }
                continue;
            }

            $user = User::find($userId);
            if ($user) {
                Notification::send($user, new OverdueTaskNotification($tasks, false));
            }
        }

        Log::info("Sent overdue task notifications for {$overdueTasks->count()} tasks");
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['overdue-tasks', 'notifications'];
    }
}