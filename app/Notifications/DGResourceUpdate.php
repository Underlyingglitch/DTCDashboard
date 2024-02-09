<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DGResourceUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected mixed $added, protected mixed $updated, protected mixed $deleted)
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
        $mail = (new MailMessage)
            ->subject('Documenten KNGU zijn bijgewerkt!')
            ->greeting('Beste ' . $notifiable->name . ',')
            ->line('Documenten op de KNGU website zijn bijgewerkt.');
        if ($this->added->count() > 0) {
            $mail->line($this->added->count() . ' document(en) toegevoegd.');
        }
        if ($this->updated->count() > 0) {
            $mail->line($this->updated->count() . ' document(en) bijgewerkt.');
        }
        if ($this->deleted->count() > 0) {
            $mail->line($this->deleted->count() . ' document(en) verwijderd.');
        }
        $mail->line('Documenten hebben op deze pagina gedurende 5 dagen een statusbadge "Nieuw" of "Bijgewerkt".');
        $mail->action('Bekijk de wijzigingen', url('/dg_resources'));
        return $mail;
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
