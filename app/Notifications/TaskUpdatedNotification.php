<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Task $task
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Task Updated: {$this->task->title}")
            ->greeting('Hello!')
            ->line("The task '{$this->task->title}' has been updated.")
            ->line("Client: {$this->task->client->name}")
            ->when($this->task->contact, fn ($mail) => $mail->line("Contact: {$this->task->contact->name}"))
            ->line("Priority: " . ucfirst($this->task->priority))
            ->line("Status: " . ucfirst($this->task->status))
            ->line("Due Date: {$this->task->due_date->format('M j, Y')}")
            ->action('View Task', url("/admin/tasks/{$this->task->id}/edit"))
            ->line('Please review the updates.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "The task '{$this->task->title}' has been updated.",
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'client_name' => $this->task->client->name,
            'contact_name' => $this->task->contact?->name,
            'priority' => $this->task->priority,
            'status' => $this->task->status,
            'due_date' => $this->task->due_date->format('Y-m-d'),
            'type' => 'task_updated',
        ];
    }
}