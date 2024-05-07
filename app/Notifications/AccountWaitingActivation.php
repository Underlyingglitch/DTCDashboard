<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountWaitingActivation extends Notification implements ShouldQueue
{
    use Queueable;

    private User $user;
    /**
     * Create a new notification instance.
     */
    public function __construct(int $user_id, private string $type, private string $club)
    {
        $this->user = User::find($user_id);
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
        return (new MailMessage)
            ->subject('Nieuw account geregistreerd!')
            ->greeting('Beste ' . $notifiable->name . ',')
            ->line($this->user->name . ' heeft een nieuw account geregistreerd! Vereniging: ' . $this->club . ', Type: ' . $this->type . '. Klik op onderstaande knop om de accounts te bekijken')
            ->action('Bekijk accounts', url('/users'));
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
