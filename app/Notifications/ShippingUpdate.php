<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class ShippingUpdate extends Notification
{
    use Queueable;

    protected $order;
    protected $trackingNumber;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $trackingNumber, $message)
    {
        $this->order = $order;
        $this->trackingNumber = $trackingNumber;
        $this->message = $message;
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
        return [
            'type' => 'shipping',
            'order_id' => $this->order->id,
            'title' => 'Update Pengiriman',
            'message' => $this->message . ' Nomor Resi: ' . $this->trackingNumber,
            'action_url' => route('orders.show', $this->order->id),
            'action_text' => 'Lacak Pesanan'
        ];
    }
}
