<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPromotion extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $url = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'type' => 'promo',
            'title' => $this->title,
            'message' => $this->message,
        ];
        
        if ($this->url) {
            $data['action_url'] = $this->url;
            $data['action_text'] = 'Lihat Promo';
        }
        
        return $data;
    }
}
