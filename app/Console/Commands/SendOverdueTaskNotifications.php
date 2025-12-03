<?php

namespace App\Console\Commands;

use App\Jobs\SendOverdueTaskNotifications;
use Illuminate\Console\Command;

class SendOverdueTaskNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'crm:send-overdue-notifications
                            {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     */
    protected $description = 'Send notifications for overdue tasks to assigned users and admins';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for overdue tasks...');

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No notifications will be sent');

            // Count overdue tasks without sending
            $overdueCount = \App\Models\Task::overdue()->count();
            $this->info("Found {$overdueCount} overdue tasks");

            if ($overdueCount > 0) {
                $this->table(
                    ['Task ID', 'Title', 'Client', 'Due Date', 'Assigned User'],
                    \App\Models\Task::overdue()
                        ->with(['client', 'user'])
                        ->get()
                        ->map(fn ($task) => [
                            $task->id,
                            $task->title,
                            $task->client->name,
                            $task->due_date->format('Y-m-d'),
                            $task->user?->name ?? 'Unassigned',
                        ])
                        ->toArray()
                );
            }

            return self::SUCCESS;
        }

        // Dispatch the job
        SendOverdueTaskNotifications::dispatch();

        $this->info('Overdue task notification job has been dispatched to the queue.');

        return self::SUCCESS;
    }
}