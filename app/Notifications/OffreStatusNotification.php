<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OffreStatusNotification extends Notification
{
    use Queueable;

    protected $offre;
    protected $isApproved;

    
    public function __construct(Offre $offre, bool $isApproved)
    {
        $this->offre = $offre;
        $this->isApproved = $isApproved;
    }

    
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; 
    }

    
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->isApproved) {
            return (new MailMessage)
                ->subject('Votre offre a été approuvée')
                ->line('Votre offre "' . $this->offre->titre . '" a été approuvée et est maintenant visible pour les candidats.')
                ->action('Voir l\'offre', url('/offres/' . $this->offre->id))
                ->line('Merci d\'utiliser notre plateforme!');
        } else {
            return (new MailMessage)
                ->subject('Votre offre a été rejetée')
                ->line('Votre offre "' . $this->offre->titre . '" a été rejetée par un administrateur.')
                ->line('Veuillez vérifier les exigences et soumettre à nouveau votre offre si nécessaire.')
                ->action('Voir l\'offre', url('/offres/' . $this->offre->id))
                ->line('Merci d\'utiliser notre plateforme!');
        }
    }

    
    public function toArray(object $notifiable): array
    {
        return [
            'offre_id' => $this->offre->id,
            'titre' => $this->offre->titre,
            'status' => $this->isApproved ? 'approuvée' : 'rejetée',
            'message' => $this->isApproved 
                ? 'Votre offre a été approuvée et est maintenant visible pour les candidats.' 
                : 'Votre offre a été rejetée par un administrateur.'
        ];
    }
}