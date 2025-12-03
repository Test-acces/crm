<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Task $task,
        public bool $isUnassigned = false
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
        $subject = $this->isUnassigned
            ? "New Unassigned Task: {$this->task->title}"
            : "New Task Assigned: {$this->task->title}";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line($this->getNotificationMessage())
            ->line("Client: {$this->task->client->name}")
            ->when($this->task->contact, fn ($mail) => $mail->line("Contact: {$this->task->contact->name}"))
            ->line("Priority: " . ucfirst($this->task->priority))
            ->line("Due Date: {$this->task->due_date->format('M j, Y')}")
            ->action('View Task', url("/admin/tasks/{$this->task->id}/edit"))
            ->line('Please review this task and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->getNotificationMessage(),
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'client_name' => $this->task->client->name,
            'contact_name' => $this->task->contact?->name,
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date->format('Y-m-d'),
            'type' => 'task_created',
            'is_unassigned' => $this->isUnassigned,
        ];
    }

    /**
     * Get the notification message.
     */
    private function getNotificationMessage(): string
    {
        if ($this->isUnassigned) {
            return "A new task '{$this->task->title}' has been created and needs to be assigned.";
        }

        return "A new task '{$this->task->title}' has been assigned to you.";
    }
}