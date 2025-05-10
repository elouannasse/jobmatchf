<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingOfferNotification extends Notification
{
    use Queueable;

    protected $offre;

  
    public function __construct(Offre $offre)
    {
        $this->offre = $offre;
    }

  
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

  
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle offre en attente d\'approbation')
            ->line('Une nouvelle offre a été soumise et est en attente d\'approbation.')
            ->line('Titre de l\'offre: ' . $this->offre->titre)
            ->line('Publiée par: ' . $this->offre->user->name)
            ->action('Examiner l\'offre', url('/admin/offres/' . $this->offre->id))
            ->line('Merci d\'utiliser notre plateforme!');
    }

  
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pending_offer',
            'offre_id' => $this->offre->id,
            'data' => [
                'offer_title' => $this->offre->titre,
                'recruteur_name' => $this->offre->user->name,
                'created_at' => $this->offre->created_at
            ]
        ];
    }
}