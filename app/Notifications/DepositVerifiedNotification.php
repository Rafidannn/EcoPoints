<?php

namespace App\Notifications;

use App\Models\WasteDeposit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DepositVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public WasteDeposit $deposit,
        public int $pointsEarned
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
            'title' => 'Setoran Terverifikasi!',
            'message' => "Setoran sampah {$this->deposit->wasteType?->name} ({$this->deposit->weight_kg} kg) telah diverifikasi. Selamat, +".number_format($this->pointsEarned).' EcoPoints telah ditambahkan ke saldo Anda!',
            'url' => route('deposits.show', $this->deposit),
            'type' => 'deposit_verified',
            'points' => $this->pointsEarned,
            'deposit_id' => $this->deposit->id,
        ];
    }
}
