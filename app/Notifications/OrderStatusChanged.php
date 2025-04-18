<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusChanged extends Notification
{
    use Queueable;

    protected $order;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
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
            'type' => 'order',
            'order_id' => $this->order->id,
            'title' => 'Status Pesanan Berubah',
            'message' => 'Pesanan #' . $this->order->order_number . ' telah ' . $this->getStatusInIndonesian(),
            'action_url' => route('orders.show', $this->order->id),
            'action_text' => 'Lihat Pesanan'
        ];
    }

    /**
     * Get the status in Indonesian language.
     */
    private function getStatusInIndonesian()
    {
        switch ($this->status) {
            case 'processing':
                return 'dikemas';
            case 'shipped':
                return 'dikirim';
            case 'completed':
                return 'selesai';
            case 'cancelled':
                return 'dibatalkan';
            default:
                return $this->status;
        }
    }
}
