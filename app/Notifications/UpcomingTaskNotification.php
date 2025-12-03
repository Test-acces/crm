<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class UpcomingTaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Collection $upcomingTasks,
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
        $taskCount = $this->upcomingTasks->count();
        $subject = $this->isAdminNotification
            ? "CRM Reminder: {$taskCount} Unassigned Upcoming Tasks"
            : "CRM Reminder: You have {$taskCount} upcoming tasks";

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting($this->isAdminNotification ? 'CRM System Reminder' : 'Hello!')
            ->line($this->getNotificationMessage());

        // Add task details
        foreach ($this->upcomingTasks as $task) {
            $daysUntilDue = now()->diffInDays($task->due_date, false);
            $dueText = $daysUntilDue === 0 ? 'Due today' : "Due in {$daysUntilDue} day" . ($daysUntilDue > 1 ? 's' : '');
            $mail->line("• **{$task->title}** - {$dueText} ({$task->due_date->format('M j, Y')})");
            $mail->line("  Client: {$task->client->name}");
            if ($task->contact) {
                $mail->line("  Contact: {$task->contact->name}");
            }
        }

        $mail->action('View Tasks', url('/admin/tasks'))
            ->line('Please review these upcoming tasks and plan accordingly.');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->getNotificationMessage(),
            'task_count' => $this->upcomingTasks->count(),
            'task_ids' => $this->upcomingTasks->pluck('id')->toArray(),
            'type' => 'upcoming_tasks',
            'is_admin' => $this->isAdminNotification,
        ];
    }

    /**
     * Get the notification message.
     */
    private function getNotificationMessage(): string
    {
        $count = $this->upcomingTasks->count();

        if ($this->isAdminNotification) {
            return "There are {$count} unassigned upcoming tasks that need attention.";
        }

        return "You have {$count} upcoming task" . ($count > 1 ? 's' : '') . " due within the next 3 days.";
    }
}