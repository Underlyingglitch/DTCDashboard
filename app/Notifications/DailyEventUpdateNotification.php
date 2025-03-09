<?php

namespace App\Notifications;

use App\Models\CalendarItem;
use App\Models\CalendarUpdate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DailyEventUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private CalendarUpdate $update)
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
        $field_names = [
            'title' => 'Titel',
            'discipline' => 'Discipline',
            'district' => 'District',
            'place' => 'Plaats',
            'location_name' => 'Locatie',
            'location_address' => 'Adres',
            'date_from' => 'Datum van',
            'date_to' => 'Datum tot',
            'results' => 'Uitslagen',
            'results_files' => 'Uitslagen bestanden',
            'program' => 'Programma',
            'program_files' => 'Programma bestanden',
            'description' => 'Omschrijving',
            'description_files' => 'Omschrijving bestanden'
        ];

        $mail =  (new MailMessage)
            ->subject($this->update->calendar_item->title . ' bijgewerkt!')
            ->greeting('Beste ' . $notifiable->name . ',')
            ->line("De wedstrijd **{$this->update->calendar_item->title}** is aangepast en u heeft zich geabonneerd op deze wedstrijd. De volgende gegevens zijn gewijzigd:");
        foreach (json_decode($this->update->value) as $item => $value) {
            $mail->line("- {$field_names[$item]}");
        }
        $mail->line('Bekijk het volledige evenement op de website van Dutchgymnastics.')
            ->action('Bekijk evenement', "https://dutchgymnastics.nl/wedstrijden-en-uitslagen?event=" . $this->update->calendar_item->event_id);

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
