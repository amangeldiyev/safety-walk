<?php

namespace App\Notifications;

use App\Models\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuditCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Audit $audit)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
                    ->line('New safety walk has been created.')
                    ->line('Site: **' . $this->audit->site->name . '**')
                    ->line('Conducted by: **' . $this->audit->user->name . '**')
                    ->line('Date: **' . $this->audit->created_at->format('Y-m-d') . '**')
                    ->when(!empty($this->audit->comment), function ($mailMessage) {
                        return $mailMessage->line('Comments: **' . $this->audit->comment . '**');
                    })
                    ->action('View Audit', url('/audits/' . $this->audit->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
