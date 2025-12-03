<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class OverdueTaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Collection $overdueTasks,
        public bool $isAdminNotification = false
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $taskCount = $this->overdueTasks->count();
        $subject = $this->isAdminNotification
            ? "CRM Alert: {$taskCount} Unassigned Overdue Tasks"
            : "CRM Alert: You have {$taskCount} overdue tasks";

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting($this->isAdminNotification ? 'CRM System Alert' : 'Hello!')
            ->line($this->getNotificationMessage());

        // Add task details
        foreach ($this->overdueTasks as $task) {
            $mail->line("• **{$task->title}** - Due: {$task->due_date->format('M j, Y')}");
            $mail->line("  Client: {$task->client->name}");
            if ($task->contact) {
                $mail->line("  Contact: {$task->contact->name}");
            }
        }

        $mail->action('View Tasks', url('/admin/tasks'))
            ->line('Please review and update these tasks as soon as possible.');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->getNotificationMessage(),
            'task_count' => $this->overdueTasks->count(),
            'task_ids' => $this->overdueTasks->pluck('id')->toArray(),
            'type' => 'overdue_tasks',
            'is_admin' => $this->isAdminNotification,
        ];
    }

    /**
     * Get the notification message.
     */
    private function getNotificationMessage(): string
    {
        $count = $this->overdueTasks->count();

        if ($this->isAdminNotification) {
            return "There are {$count} unassigned overdue tasks that need attention.";
        }

        return "You have {$count} overdue task" . ($count > 1 ? 's' : '') . " that require" . ($count > 1 ? '' : 's') . " immediate attention.";
    }
}