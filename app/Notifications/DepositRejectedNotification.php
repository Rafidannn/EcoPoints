<?php

namespace App\Notifications;

use App\Models\WasteDeposit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DepositRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public WasteDeposit $deposit
    ) {}

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
            'title' => 'Setoran Belum Dapat Diterima',
            'message' => "Setoran sampah {$this->deposit->wasteType?->name} ditolak oleh petugas. Alasan: {$this->deposit->notes}",
            'url' => route('deposits.show', $this->deposit),
            'type' => 'deposit_rejected',
            'deposit_id' => $this->deposit->id,
        ];
    }
}
