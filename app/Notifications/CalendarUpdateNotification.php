<?php

namespace App\Notifications;

use App\Models\CalendarItem;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CalendarUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected mixed $notifications)
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
            ->subject('Wedstrijdplanning bijgewerkt!')
            ->greeting('Beste ' . $notifiable->name . ',')
            ->line('De wedstrijdplanning is aangepast op gebieden waar u een melding over wenste te ontvangen.');
        foreach ($this->notifications as $item) {
            if ($item['type'] == 'created') {
                $mail->line("Nieuw: **{$item['calendar_item']['title']}** op **" . \App\Models\CalendarItem::parseDate($item['calendar_item']['date_from'], $item['calendar_item']['date_to']) . "** ({$item['calendar_item']['discipline']}|{$item['calendar_item']['district']})");
            } elseif ($item['type'] == 'updated') {
                $changes = '';
                foreach (json_decode($item['value']) as $name => $value) {
                    $changes .= $field_names[$name] . ', ';
                }
                $changes = rtrim($changes, ', ');
                $mail->line("Bijgewerkt: **{$item['calendar_item']['title']}** op **" . \App\Models\CalendarItem::parseDate($item['calendar_item']['date_from'], $item['calendar_item']['date_to']) . "** ({$item['calendar_item']['discipline']}|{$item['calendar_item']['district']}) - Wijziging(en): {$changes}");
            }
        }
        $mail->line('Bekijk het overzicht van alle wedstrijden om de wijzigingen te bekijken.')
            ->action('Bekijk de wijzigingen', url('/calendar'));

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
